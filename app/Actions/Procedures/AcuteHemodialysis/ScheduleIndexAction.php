<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AcuteHemodialysis\SlotCountable;
use App\Traits\HomePageSelectable;
use Illuminate\Support\Facades\Validator;

class ScheduleIndexAction extends AcuteHemodialysisAction
{
    use HomePageSelectable, OrderShareValidatable, SlotCountable;

    public function __invoke(array $data, User|AvatarUser $user, string $routeName): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, [
            'ref_date' => 'nullable|date',
            'full_week' => 'nullable|in:on',
        ])->validate();

        $refDate = $validated['ref_date'] ?? $this->TODAY;
        $fullWeek = $validated['full_week'] ?? false;
        $refDate = now()->create($refDate)->tz($this->TIMEZONE);
        $slots = $fullWeek
            ? [
                $refDate->clone()->addDays(-3),
                $refDate->clone()->addDays(-2),
                $refDate->clone()->addDays(-1),
            ]
            : [];

        $slots[] = $refDate;
        $slots[] = $refDate->clone()->addDay();
        $slots[] = $refDate->clone()->addDays(2);
        $slots[] = $refDate->clone()->addDays(3);

        $slots = collect($slots)->transform(function ($date) use ($user) {
            $dateNote = $date->format('Y-m-d');
            $hdUnit = $this->getNotes(dateNote: $dateNote, user: $user);
            /** Filter Chronic unit cases */
            $hdChronicUnit = $hdUnit->filter(fn ($order) => $order['dialysis_at_chronic_unit'])->values();
            $hdUnit = $hdUnit->filter(fn ($order) => ! $order['dialysis_at_chronic_unit'])->values();

            /** Filter COVID cases */
            $hdUnitCovid = $hdUnit->filter(fn ($order) => $order['covid_case'])->values();
            $hdUnit = $hdUnit->filter(fn ($order) => ! $order['covid_case'])->values();

            $ordered = $this->orderInUnitSlot($hdUnit);

            $ward = $this->getNotes(dateNote: $dateNote, user: $user, inUnit: false);
            /** Filter COVID cases */
            $wardCovid = $ward->filter(fn ($order) => $order['covid_case'])->values();
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
                'hd_unit' => [
                    'acute' => $ordered,
                    'chronic' => $hdChronicUnit,
                    'covid' => $hdUnitCovid,
                ],
                'ward' => $ward,
                'date_note' => $dateNote,
                'date_label' => $label,
                'covid_cases' => [
                    'in' => $hdUnitCovid,
                    'out' => $wardCovid,
                ],
            ];
        });

        $caseConfig = false;
        if ($shortCut = cache()->pull("acute-hemodialysis-create-order-shortcut-session-$user->id")) {
            $case = AcuteHemodialysisCaseRecord::query()->findByUnhashKey($shortCut)->firstOrFail();
            if ($this->isDialysisReservable($case)) {
                $caseConfig = [
                    'value' => implode('|', [$shortCut, $case->patient->hn, $case->patient->profile['document_id']]),
                    'label' => "HN {$case->patient->hn} {$case->patient->full_name}",
                ];
            }
        }

        $flash = $this->getFlash('Acute Hemodialysis - Schedule', $user);
        $flash['navs'] = $this->NAVS;
        $flash['action-menu'][] = $this->getSetHomePageActionMenu($routeName, $user->home_page);
        $flash['action-menu'][] = [
            'label' => 'Dialysis Session Report',
            'as' => 'a',
            'icon' => 'file-excel',
            'theme' => 'accent',
            'route' => route('procedures.acute-hemodialysis.dialysis-session.export', ['ref_date' => $validated['ref_date'] ?? null]),
            'can' => $user->can('export_acute_hemodialysis_clinical_reports'),
        ];
        $flash['action-menu'][] = [
            'label' => 'New Case Report',
            'as' => 'a',
            'icon' => 'file-excel',
            'theme' => 'accent',
            'route' => route('procedures.acute-hemodialysis.new-case.export', ['ref_date' => $validated['ref_date'] ?? null]),
            'can' => $user->can('export_acute_hemodialysis_clinical_reports'),
        ];

        return [
            'flash' => $flash,
            'slots' => $slots,
            'query' => $validated,
            'configs' => [
                'can' => [
                    'create_order' => $user->can('create_acute_hemodialysis_order'),
                ],
                'routes' => [
                    'idle_cases' => route('procedures.acute-hemodialysis.idle-cases'),
                    'resources_api_wards' => route('resources.api.wards'),
                    'resources_api_staffs' => route('resources.api.people'),
                    'staffs_scope_params' => $this->STAFF_SCOPE_PARAMS,
                    'slot_available_dates' => route('procedures.acute-hemodialysis.slot-available-dates'),
                    'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
                    'orders_export' => route('procedures.acute-hemodialysis.orders.export', ['date_note' => $refDate->format('Y-m-d')]),
                ],
                'in_unit_dialysis_types' => $this->IN_UNIT,
                'out_unit_dialysis_types' => $this->OUT_UNIT,
                'patient_types' => $this->PATIENT_TYPES,
                'hd_unit_ward' => 'ไตเทียม (Hemodialysis Unit)',
                'covid' => [
                    'route_lab' => route('resources.api.covid-lab'),
                    'route_vaccine' => route('resources.api.covid-vaccine'),
                ],
                'covid_dialysis' => ['HD 2 hrs.', 'HD 3 hrs.', 'HD 4 hrs.'],
                'case' => $caseConfig,
            ],
        ];
    }
}
