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
        foreach ($old as $key => $oldValue) {
            // In case of array value if the old one is an empty array and the new one is not
            // then the new one has no top level keys, and we should use null safe operator
            $newValue = $new[$key] ?? null;
            if ($newValue === $oldValue) {
                continue;
            }
            $diff[$key] = [$oldValue, $newValue];
        }

        return $diff;
    }
}
