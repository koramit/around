<?php

namespace App\Rules;

use App\Models\Resources\AttendingStaff;
use Illuminate\Contracts\Validation\Rule;

class NameExistsInAttendingStaffs implements Rule
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
        if ($attending = AttendingStaff::where('name', $value)->first()) {
            session()->put('validatedAttending', $attending);

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
