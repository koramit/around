<?php

namespace App\Rules;

use App\Models\Resources\Ward;

class NameExistsInWards extends CacheQueryResultRule
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
        if ($ward = Ward::where('name', $value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix.'-validatedWard', $ward);
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
