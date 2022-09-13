<?php

namespace App\Traits;

use Exception;
use Illuminate\Validation\ValidationException;

trait CommentResourceValidatable
{
    protected function getResource(array &$data)
    {
        try {
            return $data['commentable_type']::query()->findByUnhashKey($data['commentable_id'])->first();
        } catch (Exception) {
            throw ValidationException::withMessages(['status' => 'resource not found.']);
        }
    }
}
