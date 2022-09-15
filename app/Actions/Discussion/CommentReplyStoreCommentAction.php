<?php

namespace App\Actions\Discussion;

use App\Jobs\NotifyMentioned;
use Illuminate\Support\Facades\Validator;

class CommentReplyStoreCommentAction extends CommentReplyAction
{
    public function __invoke(array $data, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::make($data, [
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
        ])->validate();

        $resource = $this->getResource($validated);

        $comment = $resource->comments()->create([
            'body' => $validated['body'],
            'commentator_id' => $user->id,
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
}
