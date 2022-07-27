<?php

namespace App\Traits;

trait FirstNameAware
{
    protected function getFirstName(?string $name): string
    {
        $names = explode(' ', $name);

        return (count($names) > 2) ? $names[1] : $names[0];
    }
}
