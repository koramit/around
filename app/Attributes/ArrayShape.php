<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
class ArrayShape
{
    public function __construct(array $shape)
    {
    }
}
