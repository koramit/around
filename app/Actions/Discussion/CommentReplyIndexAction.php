<?php

namespace App\Actions\Discussion;

use Illuminate\Support\Facades\Validator;

class CommentReplyIndexAction extends CommentReplyAction
{
    public function __invoke(array $data)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, [
            'commentable_id' => 'required|string',
            'commentable_type' => 'required|string|starts_with:App\Models',
        ])->validate();

        $resource = $this->getResource($validated);

        return $resource->comments()
            ->select(['id', 'body', 'updated_at', 'commentator_id', 'parent_id'])
            ->withCount('replies')
            ->withCommentatorName()
            ->whereNull('parent_id')
            ->get()
            ->transform(fn ($c) => $this->transformComment($c));
    }
}
