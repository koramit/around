<?php

namespace App\Rules;

use App\Models\Resources\AttendingStaff;

class NameExistsInAttendingStaff extends CacheQueryResultRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($attending = AttendingStaff::where('name', $value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix.'-validatedAttending', $attending);
            }

            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.exists');
    }
}
