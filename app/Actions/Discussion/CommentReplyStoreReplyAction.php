<?php

namespace App\Actions\Discussion;

use App\Jobs\NotifyMentioned;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentReplyStoreReplyAction extends CommentReplyAction
{
    public function __invoke(string $hashedKey, array $data, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::make($data, [
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
        ])->validate();

        $parent = Comment::query()->findByUnhashKey($hashedKey)->firstOrFail();

        Comment::query()->create([
            'body' => $validated['body'],
            'parent_id' => $parent->id,
            'commentable_type' => $parent->commentable_type,
            'commentable_id' => $parent->commentable_id,
            'commentator_id' => $user->id,
        ]);

        if ($validated['notify_op']) {
            NotifyMentioned::dispatchAfterResponse($parent->commentator, $parent->commentable);
        }

        return $this->transformReplies($parent);
    }
}
