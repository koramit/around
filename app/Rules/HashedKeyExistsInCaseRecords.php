<?php

namespace App\Rules;

class HashedKeyExistsInCaseRecords extends CacheQueryResultRule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $classname = $this->subclass ?? 'App\Models\CaseRecord';
        if ($caseRecord = $classname::query()->findByUnhashKey($value)->first()) {
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
    public function message(): string
    {
        return trans('validation.exists');
    }
}
