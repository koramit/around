<?php

namespace App\Actions\Discussion;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CommentTimelineIndexAction extends CommentTimelineAction
{
    public function __invoke(array $data)
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) {
            return $link;
        }

        $validated = Validator::make($data, [
            'commentable_type' => 'required|string|starts_with:App\Models',
            'commentable_id' => 'required|string',
        ])->validate();

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
}
