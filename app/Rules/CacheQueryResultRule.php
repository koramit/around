<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CacheQueryResultRule implements Rule
{
    public function __construct(
        protected $cacheKeyPrefix = null,
        protected $subclass = null,
    ) {
    }

    public function passes($attribute, $value): bool
    {
        return false;
    }

    public function message(): string
    {
        return 'you should never use this rule';
    }
}
