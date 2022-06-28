<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CacheQueryResultRule implements Rule
{
    public function __construct(
        protected $cacheKeyPrefix = null
    ) {
    }

    public function passes($attribute, $value)
    {
        return false;
    }

    public function message()
    {
        return 'you should never use this rule';
    }
}
