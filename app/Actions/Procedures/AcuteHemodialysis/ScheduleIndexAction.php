<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;
use App\Traits\AcuteHemodialysis\SlotCountable;

class ScheduleIndexAction extends AcuteHemodialysisAction
{
    use SlotCountable;

    public function __invoke(mixed $dateNote, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api + query params
        }

        if (! $dateNote) {
            $dateNote = $this->TODAY;
        }

        $hdUnit = $this->getNotes(dateNote: $dateNote);
        $ordered = $this->orderInUnitSlot($hdUnit);

        $ward = $this->getNotes(dateNote: $dateNote, inUnit: false);
        $availableCount = ($this->LIMIT_OUT_UNIT_CASES - $ward->count());
        for ($i = 1; $i <= $availableCount; $i++) {
            $ward[] = ['type' => null];
        }

        $slot = [
            'hd_unit' => $ordered,
            'ward' => $ward,
            'date_note' => $dateNote,
        ];

        return [
            'flash' => [
                'page-title' => 'Acute Hemodialysis - Schedule',
                'main-menu-links' => $this->MENU,
                'navs' => $this->NAVS,
                'action-menu' => [],
            ],
            'slot' => $slot,
        ];
    }
}
