<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Managers\Resources\AdmissionManager;
use App\Models\CaseRecord;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;

class CaseRecordEditAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable;

    protected $LIMIT_ADVANCE_DAYS = 3;

    protected $STAFF_DIVISION_ID = 4;

    protected $FORM_CONFIGS = [
        'renal_diagnosis' => ['AKI', 'AKI ontop CKD', 'ESRD', 'Post KT'],
        'comorbidities' => [
            ['name' => 'DM', 'label' => 'DM'],
            ['name' => 'HT', 'label' => 'HT'],
            ['name' => 'DLP', 'label' => 'DLP'],
            ['name' => 'coronary_artery_disease', 'label' => 'Coronary artery disease'],
            ['name' => 'cerebrovascular_disease', 'label' => 'Cerebrovascular disease'],
            ['name' => 'COPD', 'label' => 'COPD'],
            ['name' => 'cirrhosis', 'label' => 'Cirrhosis'],
            ['name' => 'cancer', 'label' => 'Cancer'],
        ],
        'indications' => [
            ['name' => 'volume_overload', 'label' => 'Volume overload'],
            ['name' => 'metabolic_acidosis', 'label' => 'Metabolic acidosis'],
            ['name' => 'hyperkalemia', 'label' => 'Hyperkalemia'],
            ['name' => 'toxin_removal', 'label' => 'Toxin removal'],
            ['name' => 'initiate_chronic_hd', 'label' => 'Initiate Chronic HD'],
            ['name' => 'maintain_chronic_hd', 'label' => 'Maintain Chronic HD'],
            ['name' => 'change_from_pd', 'label' => 'Change from PD'],
            ['name' => 'uremia', 'label' => 'Uremia'],
            ['name' => 'delayed_graft_function', 'label' => 'Delayed graft function'],
        ],
        'insurances' => ['เบิกจ่ายตรง', 'ประกันสังคม', '30 บาท'],
        'opd_consent_form_pathname' => 'procedures/acute-hemodialysis/opd-consent-form',
        'ipd_consent_form_pathname' => 'procedures/acute-hemodialysis/ipd-consent-form',
    ];

    /**
     * @todo authorize
     */
    public function __invoke(string $hashed, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashed)->firstOrFail();

        // HD orders
        $orders = AcuteHemodialysisOrderNote::with(['patient'])
            ->WithAuthorUsername()
            ->withPlaceName(Ward::class)
            ->where('case_record_id', $caseRecord->id)
            ->orderByDesc('date_note')
            ->orderByDesc('created_at')
            ->get()
            ->transform(function ($note) use ($user) {
                return [
                    'edit_route' => route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key),
                    'ward_name' => $note->place_name,
                    'dialysis_type' => $note->meta['dialysis_type'],
                    'date_note' => $note->date_note->format('d M'),
                    'md' => $note->author_username,
                    'status' => $note->status,
                    'can' => [
                        'edit' => $user->can('edit', $note),
                        'destroy' => $user->can('destroy', $note),
                    ],
                ];
            });

        // form
        $form = $caseRecord->form;
        $form['admission'] = ($form['an'] ?? false)
                        ? (new AdmissionManager)->manage($caseRecord->form['an'])['admission']
                        : [];
        $form['record']['hashed_key'] = $caseRecord->hashed_key;
        $form['record']['hn'] = $caseRecord->patient->hn;

        // form configs
        $availableDates = [];
        $start = now()->tz($this->TIMEZONE)->addDay();
        $count = 0;
        do {
            if (! $start->isSunday()) {
                $availableDates[] = $start->format('Y-m-d');
            }
            $start->addDay();
            $count++;
        } while ($count < $this->LIMIT_ADVANCE_DAYS);
        $configs = $this->FORM_CONFIGS + [
            'in_unit_dialysis_types' => $this->IN_UNIT,
            'out_unit_dialysis_types' => $this->OUT_UNIT,
            'patient_types' => $this->PATIENT_TYPES,
            'reserve_available_dates' => $availableDates,
            'reserve_disable_dates' => [], // 'August 13, 2021',
            'image_upload_endpoints' => [
                'store' => route('uploads.store'),
                'show' => url('uploads'),
            ],
            'endpoints' => [
                'resources_api_wards' => route('resources.api.wards'),
                'resources_api_staffs' => route('resources.api.staffs'),
                'resources_api_acutehemodialysis_slot_available' => route('resources.api.acute-hemodialysis-slot-available'),
                'procedures_acutehemodialysis_orders_store' => route('procedures.acute-hemodialysis.orders.store'),
                'update' => route('procedures.acute-hemodialysis.update', $caseRecord->hashed_key),
            ],
            'staffs_scope_params' => '&division_id='.$this->STAFF_DIVISION_ID,
            'dialysis_reservable' => $this->isDialysisReservable($caseRecord),
        ];

        $flash = [
            'page-title' => 'Acute HD '.$caseRecord->patient->full_name,
            'main-menu-links' => [
                ['icon' => 'arrow-circle-left', 'label' => 'Back', 'route' => route('procedures.acute-hemodialysis.index'), 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Case Record', 'type' => '#', 'route' => '#case-record', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Orders', 'type' => '#', 'route' => '#orders', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Reservation', 'type' => '#', 'route' => '#reservation', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
            'action-menu' => [],
        ];

        return [
            'caseRecordForm' => $form,
            'formConfigs' => $configs,
            'orders' => $orders,
            'flash' => $flash,
        ];
    }
}
