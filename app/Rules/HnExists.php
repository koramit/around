<?php

namespace App\Rules;

use App\Managers\Resources\PatientManager;
use Illuminate\Contracts\Validation\Rule;

class HnExists implements Rule
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
        $patient = (new PatientManager)->manage(hn: $value, forceUpdate: true);

        return $patient['found'];
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
