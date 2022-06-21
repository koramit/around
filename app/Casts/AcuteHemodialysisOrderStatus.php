<?php

namespace App\Casts;

use App\Contracts\NoteStatusCast;

class AcuteHemodialysisOrderStatus implements NoteStatusCast
{
    protected $statuses = ['', 'draft', 'submitted', 'canceled', 'performed'];

    protected $activeStatusCodes = [1, 2]; // 'draft', 'submitted'

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
}
