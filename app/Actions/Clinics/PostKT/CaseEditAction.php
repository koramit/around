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

        if (!array_key_exists('refer', [...$case->form])) {
            $case->form['refer'] = null;
            $case->save();
        }

        $form = $case->form;
        $form['kt_no'] = $case->meta['kt_no'];
        $form['case_no'] = $case->case_no;
        $form['recipient_id'] = $case->meta['recipient_id'];
        $form['donor_type'] = $case->meta['donor_type'];
        $form['donor_id'] = $case->meta['donor_id'];
        $form['donor_redcross_id'] = $case->meta['donor_redcross_id'];
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
                'can' => $user->can('delete', $case),
            ],
            [
                'label' => 'Timestamp Update',
                'as' => 'button',
                'icon' => 'calendar-check',
                'name' => 'timestamp-update',
                'route' => route('clinics.post-kt.update', $case->hashed_key),
                'can' => $user->can('update', $case) && $user->can('view_kt_survival_follow_up_data'),
            ],
        ];
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Creatinine Chart', 'type' => '#', 'route' => '#creatinine-chart', 'can' => $user->can('view_kt_survival_clinical_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Patient Status', 'type' => '#', 'route' => '#patient-status', 'can' => $user->can('view_kt_survival_follow_up_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Graft Status', 'type' => '#', 'route' => '#graft-status', 'can' => $user->can('view_kt_survival_follow_up_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Creatinine Update', 'type' => '#', 'route' => '#creatinine-update', 'can' => $user->can('view_kt_survival_follow_up_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Clinical Data', 'type' => '#', 'route' => '#clinical-data', 'can' => $user->can('view_kt_survival_clinical_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Operation Data', 'type' => '#', 'route' => '#operation-data', 'can' => $user->can('view_kt_survival_case_data')]);
        $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Case Data', 'type' => '#', 'route' => '#case-data', 'can' => $user->can('view_kt_survival_case_data')]);
        $flash['hn'] = $case->patient->hn;
        $flash['breadcrumbs'] = $this->BREADCRUMBS;

        $configs = [
            'recipient_gender' => $case->patient->gender,
            'donor_type_options' => ['living', 'deceased'],
            'graft_status_options' => ['graft function', 'graft loss', 'loss follow up'],
            'patient_status_options' => ['alive', 'dead', 'loss follow up'],
            'dialysis_status_options' => ['on dialysis', 'not on dialysis', 'no data'],
            'autopsy_perform_options' => ['no', 'yes', 'no data'],
            'dead_place_options' => ['home'],
            'donor_cause_of_death_options' => [
                'traffic accident',
                'CVA, stroke, cerebral hemorrhage',
                'falls from height',
                'gunshot',
                'asphyxia',
                'sudden infant death syndrome',
                'primary brain tumor',
                'unknown',
            ],
            'graft_function_options' => ['immediate graft function', 'slow graft function', 'delayed graft function', 'primary non-function'],
            'male_donor_is_options' => ['ฝาแฝด', 'น้อง', 'ลูกผู้น้อง', 'พี่', 'ลูกผู้พี่', 'บุตร', 'สามี', 'บิดา', 'หลาน', 'น้า', 'อา', 'ลุง'],
            'female_donor_is_options' => ['ฝาแฝด', 'น้อง', 'ลูกผู้น้อง', 'พี่', 'ลูกผู้พี่', 'บุตร', 'ภรรยา', 'มารดา',  'หลาน', 'ป้า', 'น้า', 'อา'],
            'donor_is_options' => ['ฝาแฝด', 'น้อง', 'ลูกผู้น้อง', 'พี่', 'ลูกผู้พี่', 'บุตร', 'ภรรยา', 'สามี', 'มารดา', 'บิดา', 'หลาน', 'ป้า', 'ลุง', 'น้า', 'อา'],
            'cause_of_esrd_options' => [
                'Alport', 'Analgesic nephropathy', 'Anti-GBM', 'CGN', 'Chronic pyelonephritis', 'CresenticGN', 'CTIN', 'DM', 'DM type1', 'DM type2', 'FSGS', 'Gout', 'Graft failure', 'HT', 'IgAN', 'IgMN', 'LN', 'Membranous GN', 'Nephrocalcinosis', 'Neurogenic Bladder', 'Obstructive Uropathy', 'Pauci immune Glomerulonephritis', 'PKD', 'RAS', 'Reflux nephropathy', 'Renal dysplasia', 'RPGN', 'Single Kidney', 'Stone', 'Unknown',
            ],
            'hla_mismatch_antigens' => ['a', 'b', 'cw', 'dr', 'drb', 'dqb1', 'dpb1', 'mica', 'dqa1', 'dpa1'],
            'hla_mismatch_options' => ['0', '1', '2'],
            'crossmatches' => [
                ['name' => 'crossmatch_cdc', 'label' => 'CDC'],
                ['name' => 'crossmatch_cdc_ahg', 'label' => 'CDC-AHG'],
                ['name' => 'crossmatch_flow_cxm', 'label' => 'Flow-CXM'],
            ],
            'crossmatch_options' => ['positive', 'negative'],
            'medical_scheme_options' => ['เบิกจ่ายตรง', 'ประกันสังคม', 'สปสช', 'รัฐวิสาหกิจ', 'จ่ายเอง'],
            'routes' => [
                'show' => route('clinics.post-kt.show', $case->hashed_key),
                'update' => route('clinics.post-kt.update', $case->hashed_key),
                'timestamp_update' => route('clinics.post-kt.timestamp-update', $case->hashed_key),
                'destroy' => route('clinics.post-kt.destroy', $case->hashed_key),
                'annual_update' => route('clinics.post-kt.annual-update', $case->hashed_key),
                'annual_update_by_latest_cr' => route('clinics.post-kt.annual-update-by-latest-cr', $case->hashed_key),
                'timestamp_update_by_latest_cr' => route('clinics.post-kt.timestamp-update-by-latest-cr', $case->hashed_key),
                'hospitals' => route('resources.api.hospitals'),
                'people' => route('resources.api.people'),
                'nephrologists_scope' => '&position=8&division_id[]=6&division_id[]=9',
                'surgeons_scope' => '&position=8&division_id[]=10&division_id[]=11',
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
                'view_case_data' => $user->can('view_kt_survival_case_data'),
                'view_clinical_data' => $user->can('view_kt_survival_clinical_data'),
                'view_follow_up_data' => $user->can('view_kt_survival_follow_up_data'),
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
