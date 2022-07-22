<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AcuteHemodialysis\SlotCountable;

class ScheduleIndexAction extends AcuteHemodialysisAction
{
    use SlotCountable, OrderShareValidatable;

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
            'configs' => [
                'routes' => [
                    'idle_cases' => route('procedures.acute-hemodialysis.idle-cases'),
                    'resources_api_wards' => route('resources.api.wards'),
                    'resources_api_staffs' => route('resources.api.people'),
                    'staffs_scope_params' => $this->STAFF_SCOPE_PARAMS,
                    'slot_available_dates' => route('procedures.acute-hemodialysis.slot-available-dates')
                ],
                'in_unit_dialysis_types' => $this->IN_UNIT,
                'out_unit_dialysis_types' => $this->OUT_UNIT,
                'patient_types' => $this->PATIENT_TYPES,
                'covid' => [
                    'route_lab' => fn () => route('resources.api.covid-lab'),
                    'route_vaccine' => fn () => route('resources.api.covid-vaccine'),
                ],
            ]
        ];
    }
}
