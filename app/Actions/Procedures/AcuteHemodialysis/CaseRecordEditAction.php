<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Managers\Resources\AdmissionManager;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;

class CaseRecordEditAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable;

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
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashed)->firstOrFail();

        // HD orders
        $orders = AcuteHemodialysisOrderNote::with(['patient', 'author:id,profile'])
            ->withPlaceName(Ward::class)
            ->where('case_record_id', $caseRecord->id)
            ->orderByDesc('date_note')
            ->orderByDesc('created_at')
            ->get()
            ->transform(function ($note) use ($user) {
                $actions = collect([
                    [
                        'label' => 'Edit',
                        'type' => 'link',
                        'href' => route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key),
                        'can' => $user->can('edit', $note),
                    ],
                    [
                        'label' => 'Cancel',
                        'type' => 'button',
                        'href' => route('procedures.acute-hemodialysis.orders.destroy', $note->hashed_key),
                        'callback' => 'cancel-order',
                        'can' => $user->can('destroy', $note),
                    ],
                ])->filter(fn ($action) => $action['can']);

                return [
                    'edit_route' => route('procedures.acute-hemodialysis.orders.edit', $note->hashed_key),
                    'ward_name' => $note->place_name,
                    'dialysis_type' => $note->meta['dialysis_type'],
                    'date_note' => $note->date_note->format('d M'),
                    'md' => $note->author->first_name,
                    'status' => $note->status,
                    'actions' => $actions,
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
        $configs = $this->FORM_CONFIGS + [
            'in_unit_dialysis_types' => $this->IN_UNIT,
            'out_unit_dialysis_types' => $this->OUT_UNIT,
            'patient_types' => $this->PATIENT_TYPES,
            'today' => $this->TODAY,
            'reserve_available_dates' => $this->reserveAvailableDates(),
            'reserve_disable_dates' => [], // 'August 13, 2021',
            'image_upload_endpoints' => [
                'store' => route('uploads.store'),
                'show' => url('uploads'),
            ],
            'endpoints' => [
                'resources_api_wards' => route('resources.api.wards'),
                'resources_api_staffs' => route('resources.api.staffs'),
                'acutehemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
                'update' => route('procedures.acute-hemodialysis.update', $caseRecord->hashed_key),
            ],
            'staffs_scope_params' => '&division_id='.$this->STAFF_DIVISION_ID,
            'dialysis_reservable' => $this->isDialysisReservable($caseRecord),
        ];

        $flash = [
            'page-title' => 'Acute HD '.$caseRecord->patient->full_name,
            'hn' => $caseRecord->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Case Record', 'type' => '#', 'route' => '#case-record', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Orders', 'type' => '#', 'route' => '#orders', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Reservation', 'type' => '#', 'route' => '#reservation', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
            'action-menu' => [],
            'breadcrumbs' => $this->getBreadcumbs([
                ['label' => 'Acute HD', 'route' => route('procedures.acute-hemodialysis.index')],
            ]),
        ];

        return [
            'caseRecordForm' => $form,
            'formConfigs' => $configs,
            'orders' => $orders,
            'flash' => $flash,
        ];
    }
}
