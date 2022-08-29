<?php

namespace App\Traits;

trait StatusCastable
{
    public function get($model, $key, $value, $attributes): ?string
    {
        return $this->statuses[$value] ?? null;
    }

    public function set($model, $key, $value, $attributes): ?string
    {
        return array_search($value, $this->statuses) ?? null;
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function getCode(string $label): int
    {
        return array_search($label, $this->statuses) ?? 0;
    }
}
