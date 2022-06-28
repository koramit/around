<?php

namespace App\Rules;

use App\Models\CaseRecord;

class HashedKeyExistsInCaseRecords extends CacheQueryResultRule
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
        if ($caseRecord = CaseRecord::query()->findByUnhashKey($value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix.'-validatedCaseRecord', $caseRecord);
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
