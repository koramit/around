<?php

namespace App\Rules;

use App\Models\Resources\Person;

class NameExistsInPeople extends CacheQueryResultRule
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
        if ($person = Person::query()->where('name', $value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix.'-validatedPerson', $person);
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
