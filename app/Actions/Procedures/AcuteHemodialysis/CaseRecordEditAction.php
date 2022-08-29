<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Managers\Resources\AdmissionManager;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\Resources\Ward;
use App\Models\User;
use App\Traits\AcuteHemodialysis\CaseRecordShareValidatable;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\Subscribable;

class CaseRecordEditAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable, CaseRecordShareValidatable, Subscribable;

    protected array $FORM_CONFIGS = [
        'comorbidities' => [
            ['name' => 'dm', 'label' => 'DM'],
            ['name' => 'ht', 'label' => 'HT'],
            ['name' => 'dlp', 'label' => 'DLP'],
            ['name' => 'coronary_artery_disease', 'label' => 'Coronary artery disease'],
            ['name' => 'cerebrovascular_disease', 'label' => 'Cerebrovascular disease'],
            ['name' => 'copd', 'label' => 'COPD'],
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

    public function __invoke(string $hashed, User $user): array
    {
        /* @TODO view draft & finished note */
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashed)->firstOrFail();

        // HD orders
        $orders = AcuteHemodialysisOrderNote::query()
            ->withAuthorName()
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
                    [
                        'label' => 'View',
                        'type' => 'link',
                        'icon' => 'readme',
                        'theme' => 'accent',
                        'href' => route('procedures.acute-hemodialysis.orders.show', $order->hashed_key),
                        'can' => $user->can('view', $order),
                    ],
                ])->filter(fn ($action) => $action['can'])->values()->all();

                return [
                    'edit_route' => route('procedures.acute-hemodialysis.orders.edit', $order->hashed_key),
                    'ward_name' => $order->place_name,
                    'dialysis_type' => $order->meta['dialysis_type'],
                    'date_note' => $order->date_note,
                    'md' => $order->author_name,
                    'status' => $order->status,
                    'actions' => $actions,
                ];
            });

        // form
        if (! $caseRecord->meta['an'] && $caseRecord->created_at->diffInMinutes(now()) > 60) {
            $admission = (new AdmissionManager)->manage($caseRecord->patient->hn, true);
            if ($admission['found'] && ! $admission['admission']->dismissed_at) {
                $caseRecord->meta['an'] = $admission['admission']->an;
                $caseRecord->save();
            }
        }

        $form = $caseRecord->form;
        $form['record']['hashed_key'] = $caseRecord->hashed_key;
        $form['record']['hn'] = $caseRecord->patient->hn;

        $form['admission']['an'] = null;
        $form['admission']['admitted_at'] = null;
        $form['admission']['discharged_at'] = null;
        $form['admission']['ward_admit'] = null;
        $form['admission']['ward_discharge'] = null;
        if ($caseRecord->meta['an']) {
            $admission = (new AdmissionManager)->manage($caseRecord->meta['an'])['admission'];
            $form['admission']['an'] = $admission->an;
            $form['admission']['admitted_at'] = $admission->encountered_at->tz($this->TIMEZONE)->format('d M Y H:i');
            $form['admission']['discharged_at'] = $admission->dismissed_at?->tz($this->TIMEZONE)->format('d M Y H:i');
            if (! ($caseRecord->meta['ward_admit'] ?? false)) {
                $wards = (new AdmissionManager)->wards($caseRecord->meta['an']);
                if ($wards['found']) {
                    $wardDb = Ward::query()->where('name_ref', $wards['wards'][0]['name'])->first();
                    $caseRecord->meta['ward_admit'] = $wardDb
                        ? $wardDb->name
                        : $wards['wards'][0]['name'];
                    $caseRecord->save();
                    $form['admission']['ward_admit'] = $caseRecord->meta['ward_admit'] ?? null;
                }
            } else {
                $form['admission']['ward_admit'] = $caseRecord->meta['ward_admit'] ?? null;
            }
            $form['admission']['ward_discharge'] = $admission->dismissed_at
                ? $admission->place_name
                : null;
        }

        $form['computed']['first_dialysis_at'] = '';
        $form['computed']['first_md'] = '';
        $form['computed']['latest_dialysis_at'] = '';
        $form['computed']['latest_md'] = '';
        if ($orders->count()) {
            $orderedFiltered = $orders->filter(fn ($o) => collect(['started', 'finished'])->contains($o['status']))
                ->sortBy('date_note');
            $form['computed']['first_dialysis_at'] = $orderedFiltered->first()
                ? $orderedFiltered->first()['date_note']->format('d M Y')
                : null;
            $form['computed']['first_md'] = $orderedFiltered->first()
                ? $orderedFiltered->first()['md']
                : null;
            $form['computed']['latest_dialysis_at'] = $orderedFiltered->last()
                ? $orderedFiltered->last()['date_note']->format('d M Y')
                : null;
            $form['computed']['latest_md'] = $orderedFiltered->last()
                ? $orderedFiltered->last()['md']
                : null;
        }
        $orders->transform(function ($o) {
            $o['md'] = $this->getFirstName($o['md']);
            $o['date_note'] = $o['date_note']->format('d M');

            return $o;
        });

        // form configs
        $configs = $this->FORM_CONFIGS + [
            'renal_outcomes' => $this->RENAL_OUTCOMES,
            'patient_outcomes' => $this->PATIENT_OUTCOMES,
            'renal_diagnosis' => $this->RENAL_DIAGNOSIS,
            'serology_results' => $this->SEROLOGY_RESULTS,
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
            'comment' => $this->getCommentRoutes($caseRecord),
        ];

        $flash = [
            'page-title' => 'Acute HD '.$caseRecord->patient->full_name,
            'hn' => $caseRecord->patient->hn,
            'main-menu-links' => [
                ['icon' => 'slack-hash', 'label' => 'Case Record', 'type' => '#', 'route' => '#case-record', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Orders', 'type' => '#', 'route' => '#orders', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Discussion', 'type' => '#', 'route' => '#discussion', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ],
            'action-menu' => [
                [
                    'icon' => 'calendar-plus',
                    'label' => 'New order',
                    'route' => route('procedures.acute-hemodialysis.orders.create-shortcut', $caseRecord->hashed_key),
                    'can' => $caseRecord->status === 'active'
                        && $configs['dialysis_reservable']
                        && $user->can('create_acute_hemodialysis_order'),
                ],
                $this->getSubscriptionActionMenu($caseRecord, $user),
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
