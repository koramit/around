<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class NoteStatus implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return $this->resolveStatuses((int) $attributes['note_type_id'])[$value] ?? null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return array_search($value, $this->resolveStatuses($attributes['note_type_id'])) ?? null;
    }

    protected function resolveStatuses(int $noteTypeId)
    {
        if ($noteTypeId === 1) {
            return (new AcuteHemodialysisOrderStatus)->getStatuses();
        } else {
            return [];
        }
    }
}
