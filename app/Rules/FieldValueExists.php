<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FieldValueExists implements Rule
{
    public function __construct(
        protected string $className,
        protected string $fieldName,
        protected ?string $cacheKeyPrefix = null,
    ) {
    }

    public function passes($attribute, $value): bool
    {
        if ($model = $this->className::query()->where($this->fieldName, $value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix, $model);
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function message(): string
    {
        return trans('validation.exists');
    }
}
