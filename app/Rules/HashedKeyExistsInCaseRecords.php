<?php

namespace App\Rules;

use App\Models\CaseRecord;
use Illuminate\Contracts\Validation\Rule;

class HashedKeyExistsInCaseRecords implements Rule
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
            session()->put('validatedCaseRecord', $caseRecord);

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