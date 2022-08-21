<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyMentioned;
use App\Models\Comment;
use App\Traits\FirstNameAware;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentReplyController extends Controller
{
    use FirstNameAware;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
        ]);

        try {
            $resource = $validated['commentable_type']::query()->findByUnhashKey($validated['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }

        $comment = $resource->comments()->create([
            'body' => $validated['body'],
            'commentator_id' => $request->user()->id,
        ]);

        if (! $validated['notify_op']) {
            return $this->transformComment($comment);
        }

        $op = $resource->author ?? $resource->creator;

        if ($op) {
            NotifyMentioned::dispatchAfterResponse($op, $resource);
        }

        return $this->transformComment($comment);
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
        ]);

        try {
            $resource = $validated['commentable_type']::query()->findByUnhashKey($validated['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }

        return $resource->comments()
            ->select(['id', 'body', 'updated_at', 'commentator_id', 'parent_id'])
            ->withCount('replies')
            ->withCommentatorName()
            ->whereNull('parent_id')
            ->get()
            ->transform(fn ($c) => $this->transformComment($c));
    }

    public function show(string $hashedKey)
    {
        $comment = Comment::query()->findByUnhashKey($hashedKey)->firstOrFail();

        return $this->transformReplies($comment);
    }

    public function reply(string $hashedKey, Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
        ]);

        $parent = Comment::query()->findByUnhashKey($hashedKey)->firstOrFail();

        Comment::query()->create([
            'body' => $validated['body'],
            'parent_id' => $parent->id,
            'commentable_type' => $parent->commentable_type,
            'commentable_id' => $parent->commentable_id,
            'commentator_id' => $request->user()->id,
        ]);

        if ($validated['notify_op']) {
            NotifyMentioned::dispatchAfterResponse($parent->commentator, $parent->commentable);
        }

        return $this->transformReplies($parent);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    protected function transformReplies(Comment $parent)
    {
        return $parent->replies()
            ->select(['id', 'body', 'updated_at', 'commentator_id', 'parent_id'])
            ->withCount('replies')
            ->withCommentatorName()
            ->get()
            ->transform(fn ($r) => $this->transformComment($r));
    }

    protected function transformComment(Comment $comment): array
    {
        return [
            'id' => $comment->hashed_key,
            'body' => $comment->body_html,
            'commentator' => $this->getFirstName($comment->commentator_name ?? $comment->commentator->full_name),
            'at' => $comment->updated_at->longRelativeToNowDiffForHumans(),
            'replies_count' => $comment->replies_count ?? 0,
            'routes' => [
                'show' => route('comments.reply-oriented.show', $comment->hashed_key),
                'reply' => route('comments.reply-oriented.reply', $comment->hashed_key),
            ],
        ];
    }
}
