<?php

namespace App\Traits\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisOrderStatus as NoteStatus;
use App\Models\CaseRecord;

trait OrderShareValidatable
{
    protected $IN_UNIT = [
        'HD 2 hrs.',
        'HD 3 hrs.',
        'HD 4 hrs.',
        'HD+HF 4 hrs.',
        'HD+TPE 6 hrs.',
        'HF 2 hrs.',
        'TPE 2 hrs.',
    ];

    protected $OUT_UNIT = [
        'HD 2 hrs.',
        'HD 3 hrs.',
        'HD 4 hrs.',
        'HD+HF 4 hrs.',
        'HD+TPE 6 hrs.',
        'HF 2 hrs.',
        'TPE 2 hrs.',
        'SLEDD',
    ];

    protected function getAllDialysisType()
    {
        return collect($this->IN_UNIT)->merge($this->OUT_UNIT)->unique()->values()->all();
    }

    public function isDialysisReservable(CaseRecord $caseRecord): bool
    {
        $activeNoteCount = $caseRecord->notes()
            ->where('note_type_id', $this->ACUTE_HD_ORDER_NOTE_TYPE_ID)
            ->whereIn('status', (new NoteStatus)->getActiveStatusCodes())
            ->count();

        return $activeNoteCount === 0;
    }
}
