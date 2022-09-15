<?php

namespace App\Actions\Discussion;

use App\Jobs\NotifyMentioned;
use App\Rules\HashedKeyIdExists;
use Illuminate\Support\Facades\Validator;

class CommentTimelineStoreAction extends CommentTimelineAction
{
    public function __invoke(array $data, mixed $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $cacheKeyPrefix = "uid-$user->id-comment-validated-by-hashed-key";
        $validated = Validator::make($data, [
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
            'body' => 'required|string|max:1024',
            'notify_op' => 'required|bool',
            'parent_id' => ['nullable', new HashedKeyIdExists('App\Models\Comment', $cacheKeyPrefix)],
        ])->validate();

        $resource = $this->getResource($validated);

        $parent = cache()->pull($cacheKeyPrefix);

        $comment = $resource->comments()->create([
            'body' => $validated['body'],
            'commentator_id' => $user->id,
            'parent_id' => $parent?->id,
        ]);

        if (! $validated['notify_op']) {
            return $this->transformComment($comment);
        }

        $op = $parent
            ? $parent->commentator
            : ($resource->author ?? $resource->creator);

        if ($op) {
            NotifyMentioned::dispatchAfterResponse($op, $resource);
        }

        return $this->transformComment($comment);
    }
}
