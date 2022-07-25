<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AcuteHemodialysis\SlotCountable;
use Illuminate\Support\Facades\Validator;

class ScheduleIndexAction extends AcuteHemodialysisAction
{
    use SlotCountable, OrderShareValidatable;

    public function __invoke(array $data, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api + query params
        }

        $validated = Validator::make($data, [
            'ref_date' => 'nullable|date',
            'full_week' => 'nullable|in:on'
        ])->validate();

        $refDate = $validated['ref_date'] ?? $this->TODAY;
        $fullWeek = $validated['full_week'] ?? false;
        $refDate = now()->create($refDate)->tz($this->TIMEZONE);
        $slots = $fullWeek
            ? [
                $refDate->clone()->addDays(-3),
                $refDate->clone()->addDays(-2),
                $refDate->clone()->addDays(-1)
            ]
            : [];

        $slots[] = $refDate;
        $slots[] = $refDate->clone()->addDay();
        $slots[] = $refDate->clone()->addDays(2);
        $slots[] = $refDate->clone()->addDays(3);

        $slots = collect($slots)->transform(function ($date) use ($user) {
            $dateNote = $date->format('Y-m-d');
            $hdUnit = $this->getNotes(dateNote: $dateNote, user: $user);
            /** Filter COVID cases */
            $hdUnitCovid = $hdUnit->filter(fn ($order) => $order['covid_case']);
            $hdUnit = $hdUnit->filter(fn ($order) => ! $order['covid_case']);
            $ordered = $this->orderInUnitSlot($hdUnit);

            $ward = $this->getNotes(dateNote: $dateNote, user: $user, inUnit: false);
            /** Filter COVID cases */
            $wardCovid = $ward->filter(fn ($order) => $order['covid_case']);
            $ward = $ward->filter(fn ($order) => ! $order['covid_case']);
            $availableCount = ($this->LIMIT_OUT_UNIT_CASES - $ward->count());
            for ($i = 1; $i <= $availableCount; $i++) {
                $ward[] = ['type' => null];
            }

            $label = $date->format('j M - ');

            if ($date->isToday()) {
                $label .= 'Today';
            } else {
                $label .= $date->dayName;
            }

            return [
                'hd_unit' => $ordered,
                'ward' => $ward,
                'date_note' => $dateNote,
                'date_label' => $label,
                'covid_cases' => [
                    'in' => $hdUnitCovid,
                    'out' => $wardCovid,
                ],
            ];
        });

        return [
            'flash' => [
                'page-title' => 'Acute Hemodialysis - Schedule',
                'main-menu-links' => $this->MENU,
                'navs' => $this->NAVS,
                'action-menu' => [],
            ],
            'slots' => $slots,
            'query' => $validated,
            'configs' => [
                'routes' => [
                    'idle_cases' => route('procedures.acute-hemodialysis.idle-cases'),
                    'resources_api_wards' => route('resources.api.wards'),
                    'resources_api_staffs' => route('resources.api.people'),
                    'staffs_scope_params' => $this->STAFF_SCOPE_PARAMS,
                    'slot_available_dates' => route('procedures.acute-hemodialysis.slot-available-dates'),
                    'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
                ],
                'in_unit_dialysis_types' => $this->IN_UNIT,
                'out_unit_dialysis_types' => $this->OUT_UNIT,
                'patient_types' => $this->PATIENT_TYPES,
                'covid' => [
                    'route_lab' => fn () => route('resources.api.covid-lab'),
                    'route_vaccine' => fn () => route('resources.api.covid-vaccine'),
                ],
            ],
        ];
    }
}
