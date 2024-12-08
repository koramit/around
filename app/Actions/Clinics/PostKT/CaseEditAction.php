<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Carbon;

class CaseEditAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);

        $dateTx = Carbon::create($case->meta['date_transplant']);
        $dateGraftLoss = $case->form['date_graft_loss']
            ? Carbon::create($case->form['date_graft_loss'])
            : null;

        $yearTh = $dateGraftLoss
            ? $dateGraftLoss->year - $dateTx->year
            : Carbon::now()->year - $dateTx->year;

        $case->form['annual_year'] = $yearTh;
        if (isset($case->form["year_{$yearTh}_cr"])) {
            $case->form['annual_cr'] = $case->form["year_{$yearTh}_cr"];
            $case->form['date_annual_cr'] = Carbon::create($case->form["date_year_{$yearTh}_cr"])->format('M d, Y');
        }

        $form = $case->form;
        $form['kt_no'] = $case->meta['kt_no'];
        $form['kt_id'] = $case->meta['kt_id'];
        $form['date_transplant'] = $case->meta['date_transplant'];
        $form['date_transplant_formatted'] = Carbon::create($case->meta['date_transplant'])->format('M d, Y');
        $form['date_last_update_formatted'] = Carbon::create($case->form['date_last_update'])->format('M d, Y');
        $form['date_latest_cr_formatted'] = $form['date_latest_cr']
            ? Carbon::create($case->form['date_latest_cr'])->format('M d, Y')
            : null;
        $form['no_patient_record'] = $case->meta['no_patient_record'];
        if ($form['no_patient_record']) {
            if ($case->meta['no_patient_dob']) {
                $form['no_patient_record_message'] = 'The patient record and the DOB were not found.';
            } else {
                $form['no_patient_record_message'] = 'The patient record was not found.';
            }
        }
        foreach ($form as $field => $value) {
            if (in_array(gettype($value), ['double', 'float', 'integer'])) {
                $form[$field] = (string) $value;
            }
        }
        $flash = $this->getFlash($case->title, $user);
        $flash['action-menu'] = [
            [
                'label' => 'Delete',
                'as' => 'button',
                'icon' => 'trash',
                'theme' => 'danger',
                'route' => route('wards.kt-admission.destroy', $case->hashed_key),
                'name' => 'destroy-case',
                'config' => [
                    'heading' => 'Delete Case',
                    'confirmText' => $case->title,
                    'requireReason' => false,
                ],
                'can' => $user->can('update', $case),
            ],
            [
                'label' => 'Timestamp Update',
                'as' => 'button',
                'icon' => 'calendar-check',
                'name' => 'timestamp-update',
                'route' => route('clinics.post-kt.update', $case->hashed_key),
                'can' => $user->can('update', $case),
            ],
        ];
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Creatinine Chart', 'type' => '#', 'route' => '#creatinine-chart', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Patient Status', 'type' => '#', 'route' => '#patient-status', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Graft Status', 'type' => '#', 'route' => '#graft-status', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Creatinine Update', 'type' => '#', 'route' => '#creatinine-update', 'can' => true]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Case Data', 'type' => '#', 'route' => '#case-data', 'can' => true]);
        $flash['hn'] = $case->patient->hn;
        $flash['breadcrumbs'] = $this->BREADCRUMBS;

        $configs = [
            'graft_status_options' => ['graft function', 'graft loss', 'loss follow up'],
            'patient_status_options' => ['alive', 'dead', 'loss follow up'],
            'dialysis_status_options' => ['on dialysis', 'not on dialysis', 'no data'],
            'autopsy_perform_options' => ['no', 'yes', 'no data'],
            'dead_place_options' => ['home'],
            'routes' => [
                'update' => route('clinics.post-kt.update', $case->hashed_key),
                'timestamp_update' => route('clinics.post-kt.timestamp-update', $case->hashed_key),
                'destroy' => route('clinics.post-kt.destroy', $case->hashed_key),
                'annual_update' => route('clinics.post-kt.annual-update', $case->hashed_key),
                'annual_update_by_latest_cr' => route('clinics.post-kt.annual-update-by-latest-cr', $case->hashed_key),
                'timestamp_update_by_latest_cr' => route('clinics.post-kt.timestamp-update-by-latest-cr', $case->hashed_key),
            ],
            'can' => [
                'update' => $user->can('update', $case),
                'use_latest_cr_to_update_timestamps' => ! isset($form['annual_cr'])
                    && (float) $form['latest_cr'] <= 4.0
                    && $case->status === KidneyTransplantSurvivalCaseStatus::ACTIVE,
                'use_latest_cr_as_annual_cr' => $case->status === KidneyTransplantSurvivalCaseStatus::ACTIVE
                    && $yearTh !== 0
                    && ! isset($form['annual_cr'])
                    && $form['latest_cr'],
                'annual_update' => $case->status === KidneyTransplantSurvivalCaseStatus::ACTIVE,
            ],
        ];

        if (! $configs['can']['update']) {
            $flash['message'] = [
                'type' => 'warning',
                'title' => 'Read only mode.',
                'message' => 'Case Record cannot be saved.',
            ];
        }

        return [
            'formData' => $form,
            'flash' => $flash,
            'formConfigs' => $configs,
        ];
    }
}
