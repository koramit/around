<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SelectAtLeastOne implements Rule
{
    public function passes($attribute, $value): bool
    {
        foreach ($value as $key => $dataValue) {
            if ($dataValue) {
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        return trans('Must be at least one :attribute.');
    }
}
