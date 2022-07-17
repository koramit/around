<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class AcuteHemodialysisOrderStatus implements CastsAttributes, NoteStatusCast
{
    protected array $statuses = ['', 'draft', 'submitted', 'scheduling', 'canceled', 'performed', 'expired', 'disapproved'];

    protected array $activeStatusCodes = [1, 2, 3]; // 'draft', 'submitted', 'scheduling'

    protected array $slotOccupiedStatusCodes = [1, 2, 3, 5]; // 'draft', 'submitted', 'scheduling', 'performed'

    protected array $editNotAllowStatuses = ['canceled', 'performed', 'expired', 'disapproved'];

    protected array $updateNotAllowStatuses = ['submitted', 'canceled', 'performed', 'expired', 'disapproved'];

    protected array $scheduleNotAllowStatuses = ['scheduling', 'canceled', 'performed', 'expired', 'disapproved'];

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

    public function getActiveStatusCodes(): array
    {
        return $this->activeStatusCodes;
    }

    public function getSlotOccupiedStatusCodes(): array
    {
        return $this->slotOccupiedStatusCodes;
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
