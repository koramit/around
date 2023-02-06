<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SelectAtLeastOne implements Rule
{
    protected string $attribute;

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        foreach ($value as $key => $dataValue) {
            if ($dataValue) {
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        $temp = explode('.', $this->attribute);
        if (count($temp)) {
            foreach ($temp as $key => $value) {
                if (is_numeric($value)) {
                    $temp[$key] = '#'.($value + 1);
                }
            }

            return trans('Must be at least one '.implode(' ', $temp).'.');
        }

        return trans('Must be at least one :attribute.');
    }
}
