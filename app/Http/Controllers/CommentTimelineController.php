<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Notifications\AlertMentioned;
use App\Rules\HashedKeyIdExists;
use App\Traits\FirstNameAware;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CommentTimelineController extends Controller
{
    use FirstNameAware;

    public function store(Request $request)
    {
        $cacheKeyPrefix = "uid-{$request->user()->id}-comment-validated-by-hashed-key";
        $validated = $request->validate([
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
            'parent_id' => ['nullable', new HashedKeyIdExists('App\Models\Comment', $cacheKeyPrefix)],
        ]);

        try {
            $resource = $validated['commentable_type']::query()->findByUnhashKey($validated['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }

        $parent = cache()->pull($cacheKeyPrefix);

        $comment = $resource->comments()->create([
            'body' => $validated['body'],
            'commentator_id' => $request->user()->id,
            'parent_id' => $parent?->id,
        ]);

        if (! $validated['notify_op']) {
            return $this->transformComment($comment);
        }

        $op = $parent
            ? $parent->commentator
            : ($resource->author ?? $resource->creator);

        if ($op) {
            $op->notify(new AlertMentioned($resource));
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
            ->with('parent:id,body')
            ->withCommentatorName()
            ->get()
            ->transform(fn ($c) => $this->transformComment($c));
    }

    public function reply()
    {
    }

    protected function transformComment(Comment $comment): array
    {
        $parentBody = $comment->parent
            ? collect(explode("\n", Str::limit($comment->parent->body, 50)))
                ->transform(fn ($line) => "<p>$line</p>")
                ->join('')
            : null;

        return [
            'id' => $comment->hashed_key,
            'body' => $comment->body_html,
            'commentator' => $this->getFirstName($comment->commentator_name ?? $comment->commentator->full_name),
            'at' => $comment->updated_at->longRelativeToNowDiffForHumans(),
            'parent_id' => $comment->parent?->hashed_key,
            'parent_body' => $parentBody,
            'replies_count' => 0,
        ];
    }
}
