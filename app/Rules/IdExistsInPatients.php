<?php

namespace App\Rules;

use App\Models\Resources\Patient;
use Illuminate\Contracts\Validation\Rule;

class IdExistsInPatients implements Rule
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
        if ($patient = Patient::find($value)) {
            session()->put('validatedPatient', $patient);

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
