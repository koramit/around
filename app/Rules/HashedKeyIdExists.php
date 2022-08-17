<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HashedKeyIdExists implements Rule
{
    public function __construct(
        protected string $className,
        protected ?string $cacheKeyPrefix = null,
    ) {
    }

    public function passes($attribute, $value): bool
    {
        if ($model = $this->className::query()->findByUnhashKey($value)->first()) {
            if ($this->cacheKeyPrefix) {
                cache()->put($this->cacheKeyPrefix, $model);
            }

            return true;
        }

        return false;
    }

    public function message(): string
    {
        return trans('validation.exists');
    }
}
