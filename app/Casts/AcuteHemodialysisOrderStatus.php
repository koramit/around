<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class AcuteHemodialysisOrderStatus implements CastsAttributes, NoteStatusCast
{
    protected $statuses = ['', 'draft', 'submitted', 'scheduling', 'canceled', 'performed', 'expired'];

    protected $activeStatusCodes = [1, 2, 3]; // 'draft', 'submitted', 'scheduling'

    protected $editNotAllowStatuses = ['canceled', 'performed', 'expired'];

    protected $updateNotAllowStatuses = ['submitted', 'canceled', 'performed', 'expired'];

    protected $scheduleNotAllowStatuses = ['scheduling', 'canceled', 'performed', 'expired'];

    /** Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return $this->statuses[$value] ?? null;
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

    public function getActiveStatusCodes(): array
    {
        return $this->activeStatusCodes;
    }

    public function getEditNotAllowStatuses(): Collection
    {
        return collect($this->editNotAllowStatuses);
    }

    public function getUpdateNotAllowStatuses(): Collection
    {
        return collect($this->updateNotAllowStatuses);
    }

    public function getSubmitNotAllowStatuses(): Collection
    {
        return collect($this->editNotAllowStatuses);
    }

    public function getScheduleNotAllowStatuses(): Collection
    {
        return collect($this->scheduleNotAllowStatuses);
    }
}
