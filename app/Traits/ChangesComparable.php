<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait ChangesComparable
{
    protected function formJsonDiff(mixed $old, mixed $new): array
    {
        $old = Arr::dot($old);
        $new = Arr::dot($new);
        $diff = [];
        foreach ($old as $key => $value) {
            if ($new[$key] === $value) {
                continue;
            }
            $diff[$key] = [$value, $new[$key]];
        }

        return $diff;
    }
}
