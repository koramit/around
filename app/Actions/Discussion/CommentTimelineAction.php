<?php

namespace App\Actions\Discussion;

use App\Models\Comment;
use App\Traits\AvatarLinkable;
use App\Traits\CommentResourceValidatable;
use App\Traits\FirstNameAware;
use Illuminate\Support\Str;

class CommentTimelineAction
{
    use AvatarLinkable, CommentResourceValidatable, FirstNameAware;

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
