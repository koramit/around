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

    protected array $FORM_CONFIGS = [
        'renal_diagnosis' => ['AKI', 'AKI on top CKD', 'ESRD', 'Post KT'],
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
        'serology_results' => ['positive', 'intermediate', 'negative'],
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
        $orders = AcuteHemodialysisOrderNote::query()
            ->with(['author:id,profile'])
            ->withPlaceName(Ward::class)
            ->where('case_record_id', $caseRecord->id)
            ->orderBy('status')
            ->orderByDesc('date_note')
            ->orderByDesc('created_at')
            ->get()
            ->transform(function (AcuteHemodialysisOrderNote $order) use ($user) {
                $actions = collect([
                    [
                        'label' => 'Cancel',
                        'type' => 'button',
                        'icon' => 'trash',
                        'theme' => 'warning',
                        'href' => route('procedures.acute-hemodialysis.orders.destroy', $order->hashed_key),
                        'callback' => 'cancel-order',
                        'confirm_text' => $order->cancel_confirm_text,
                        'can' => $user->can('destroy', $order),
                    ],
                    [
                        'label' => 'Edit',
                        'type' => 'link',
                        'icon' => 'edit',
                        'theme' => 'accent',
                        'href' => route('procedures.acute-hemodialysis.orders.edit', $order->hashed_key),
                        'can' => $user->can('edit', $order),
                    ],
                ])->filter(fn ($action) => $action['can'])->values()->all();

                return [
                    'edit_route' => route('procedures.acute-hemodialysis.orders.edit', $order->hashed_key),
                    'ward_name' => $order->place_name,
                    'dialysis_type' => $order->meta['dialysis_type'],
                    'date_note' => $order->date_note->format('d M'),
                    'md' => $order->author->first_name,
                    'status' => $order->status,
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
                'resources_api_staffs' => route('resources.api.people'),
                'acute_hemodialysis_slot_available' => route('procedures.acute-hemodialysis.slot-available'),
                'orders_store' => route('procedures.acute-hemodialysis.orders.store'),
                'update' => route('procedures.acute-hemodialysis.update', $caseRecord->hashed_key),
            ],
            'staffs_scope_params' => $this->STAFF_SCOPE_PARAMS,
            'dialysis_reservable' => $this->isDialysisReservable($caseRecord),
            'covid' => [
                'hn' => $caseRecord->patient->hn,
                'cid' => $caseRecord->patient->profile['document_id'],
                'route_lab' => fn () => route('resources.api.covid-lab'),
                'route_vaccine' => fn () => route('resources.api.covid-vaccine'),
            ],
        ];

        $flash = [
            'page-title' => 'Acute HD '.$caseRecord->patient->full_name,
            'hn' => $caseRecord->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Case Record', 'type' => '#', 'route' => '#case-record', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Orders', 'type' => '#', 'route' => '#orders', 'can' => true],
                //                ['icon' => 'slack-hash', 'label' => 'Reservation', 'type' => '#', 'route' => '#reservation', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ],
            'action-menu' => [
                [
                    'icon' => 'calendar-plus',
                    'label' => 'New order',
                    'route' => route('procedures.acute-hemodialysis.orders.create-shortcut', $caseRecord->hashed_key),
                    'can' => $configs['dialysis_reservable'] && $user->can('create_acute_hemodialysis_order'),
                ],
            ],
            'breadcrumbs' => $this->BREADCRUMBS,
        ];

        return [
            'caseRecordForm' => $form,
            'formConfigs' => $configs,
            'orders' => $orders,
            'flash' => $flash,
        ];
    }
}
