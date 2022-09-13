<?php

namespace App\Actions\Discussion;

use App\Models\Comment;

class CommentReplyShowRepliesAction extends CommentReplyAction
{
    public function __invoke(string $hashedKey)
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) {
            return $link;
        }

        $comment = Comment::query()->findByUnhashKey($hashedKey)->firstOrFail();

        return $this->transformReplies($comment);
    }
}
