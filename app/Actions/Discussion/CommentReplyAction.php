<?php

namespace App\Actions\Discussion;

use App\Models\Comment;
use App\Traits\AvatarLinkable;
use App\Traits\CommentResourceValidatable;
use App\Traits\FirstNameAware;

class CommentReplyAction
{
    use AvatarLinkable, CommentResourceValidatable, FirstNameAware;

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
