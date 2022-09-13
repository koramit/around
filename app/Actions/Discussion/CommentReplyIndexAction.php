<?php

namespace App\Actions\Discussion;

use Illuminate\Support\Facades\Validator;

class CommentReplyIndexAction extends CommentReplyAction
{
    public function __invoke(array $data)
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) { // empty array exception
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
