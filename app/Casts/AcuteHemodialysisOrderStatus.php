<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;
use App\Traits\StatusCastable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;

class AcuteHemodialysisOrderStatus implements CastsAttributes, NoteStatusCast
{
    use StatusCastable;

    protected array $statuses = [
        '',
/**  1 */ 'draft',
/**  2 */ 'submitted',
/**  3 */ 'scheduling',
/**  4 */ 'canceled',
/**  5 */ 'started',
/**  6 */ 'finished',
/**  7 */ 'expired',
/**  8 */ 'disapproved',
    ];

    protected array $activeStatusCodes = [1, 2, 3]; // 'draft', 'submitted', 'scheduling'

    protected array $slotOccupiedStatusCodes = [1, 2, 3, 5, 6]; // 'draft', 'submitted', 'scheduling', 'started', 'finished

    protected array $editNotAllowStatuses = ['canceled', 'started', 'finished', 'expired', 'disapproved'];

    protected array $updateNotAllowStatuses = ['submitted', 'canceled', 'started', 'finished', 'expired', 'disapproved'];

    protected array $scheduleNotAllowStatuses = ['scheduling', 'canceled', 'started', 'finished', 'expired', 'disapproved'];

    protected array $performNotAllowStatuses = ['canceled', 'expired', 'disapproved'];

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

    public function getPerformNotAllowStatuses(): Collection
    {
        return collect($this->performNotAllowStatuses);
    }
}
