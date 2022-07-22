<?php

namespace App\Rules;

use App\Models\Resources\Patient;
use Illuminate\Contracts\Validation\InvokableRule;

class HnExistsInPatients implements InvokableRule
{
    public function __invoke($attribute, $value, $fail)
    {
        if(!Patient::query()->findByHashedKey($value)->first()) {
            $fail(trans('validation.exists'));
        }
    }
}