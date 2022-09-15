<?php

namespace App\Actions\Discussion;

use Illuminate\Support\Facades\Validator;

class CommentTimelineIndexAction extends CommentTimelineAction
{
    public function __invoke(array $data)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, [
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
        ])->validate();

        $resource = $this->getResource($validated);

        return $resource->comments()
            ->select(['id', 'body', 'updated_at', 'commentator_id', 'parent_id'])
            ->with('parent:id,body')
            ->withCommentatorName()
            ->get()
            ->transform(fn ($c) => $this->transformComment($c));
    }
}
