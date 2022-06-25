<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AcceptedIfOthersFalsy implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected array $data,
    ) {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value === true) {
            return true;
        }

        array_shift($this->data);
        foreach ($this->data as $key => $dataValue) {
            if ($dataValue) {
                return true;
            }
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
        return trans('validation.accepted');
    }
}
