<?php

namespace App\Rules;

use App\Models\Resources\Ward;
use Illuminate\Contracts\Validation\Rule;

class NameExistsInWards implements Rule
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
            session()->put('validatedWard', $ward);

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
