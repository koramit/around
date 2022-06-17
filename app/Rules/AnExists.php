<?php

namespace App\Rules;

use App\Managers\Resources\AdmissionManager;
use Illuminate\Contracts\Validation\Rule;

class AnExists implements Rule
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
        $admission = (new AdmissionManager)->manage(key: $value);

        return $admission['found'];
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
