<?php

namespace App\Traits;

trait FirstNameAware
{
    protected function getFirstName(?string $name): string
    {
        $names = explode(' ', $name ?? '');
        $count = count($names);
        if ($count === 1) {
            return $names[0];
        } elseif ($count === 2) { // most likely first + last
            return $names[0];
        } elseif ($count === 3) { // most likely title + first + last
            return $names[1];
        } elseif ($count === 4) {
            if (str_ends_with($names[1], '.')) { // title1. + title2. + first + last
                return $names[2];
            } else { // title + first + middle + last
                return $names[1];
            }
        } else {
            return $name;
        }
    }
}
