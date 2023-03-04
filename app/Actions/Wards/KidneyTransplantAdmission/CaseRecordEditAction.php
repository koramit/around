<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Resources\Admission;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

class CaseRecordEditAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('edit', $caseRecord) && $user->cannot('addendum', $caseRecord)) {
            abort(403, 'You are not allowed to edit this case record.');
        }

        $form = $this->getForm($caseRecord->form);
        $admission = Admission::query()->findByHashKey($caseRecord->meta['an'])->first();
        $form['reason_for_admission'] = $caseRecord->meta['reason_for_admission'];
        $form['admitted_at'] = $admission->encountered_at->tz(7)->format('d M y');
        $form['discharged_at'] = $admission->dismissed_at?->tz(7)->format('d M y');
        $form['discharge_status'] = $admission->meta['discharge_status'];
        $form['discharge_type'] = $admission->meta['discharge_type'];
        $form['ward_discharged'] = $admission->dismissed_at ? $admission->place->name : null;
        $form['los'] = $admission->length_of_stay;
        $configs = [
            'routes' => [
                'people' => route('resources.api.people'),
                'nephrologists_scope' => '&position=8&division_id[]=6&division_id[]=9',
                'surgeons_scope' => '&position=8&division_id[]=10&division_id[]=11',
                'wards' => route('resources.api.wards'),
                'upload' => [
                    'store' => route('uploads.store'),
                    'show' => route('uploads.show'),
                    'destroy' => route('uploads.destroy'),
                ],
                'update' => route('wards.kt-admission.update', $caseRecord->hashed_key),
                'complete' => route('wards.kt-admission.complete', $caseRecord->hashed_key),
                'destroy' => route('wards.kt-admission.destroy', $caseRecord->hashed_key),
                'addendum' => route('wards.kt-admission.addendum', $caseRecord->hashed_key),
                'cancel' => route('wards.kt-admission.cancel', $caseRecord->hashed_key),
            ],
            'can' => [
                'update' => $user->can('update', $caseRecord),
                'complete' => $user->can('complete', $caseRecord),
                'destroy' => $user->can('destroy', $caseRecord),
                'addendum' => $user->can('addendum', $caseRecord),
                'cancel' => $user->can('cancel', $caseRecord),
            ],
            'attachment_upload_pathname' => $this->CONFIGS['attachment_upload_pathname'],
            'insurances' => ['เบิกจ่ายตรง', 'ประกันสังคม', '30 บาท', 'รัฐวิสาหกิจ'],
            'common_transfer_wards' => ['หอผู้ป่วยโรคไต สง่า นิลวรางกูร', 'เฉลิมพระเกียรติ ชั้น 7 เหนือ'],
            'donor_types' => $this->CONFIGS['donor_types'],
            'cause_of_esrd_options' => [
                'Alport', 'Analgesic nephropathy', 'Anti-GBM', 'CGN', 'Chronic pyelonephritis', 'CresenticGN', 'CTIN', 'DM', 'DM type1', 'DM type2', 'FSGS', 'Gout', 'Graft failure', 'HT', 'IgAN', 'IgMN', 'LN', 'Membranous GN', 'Nephrocalcinosis', 'Neurogenic Bladder', 'Obstructive Uropathy', 'Pauci immune Glomerulonephritis', 'PKD', 'RAS', 'Reflux nephropathy', 'Renal dysplasia', 'RPGN', 'Single Kidney', 'Stone', 'Unknown',
            ],
            'abo_options' => $this->CONFIGS['abo_options'],
            'rh_options' => $this->CONFIGS['rh_options'],
            'hla_mismatch_antigens' => ['A', 'B', 'DR', 'DQ'],
            'hla_mismatch_options' => $this->CONFIGS['hla_mismatch_options'],
            'recipient_is_options' => $caseRecord->patient->gender === 'male'
                ? $this->CONFIGS['male_recipient_is_options']
                : $this->CONFIGS['female_recipient_is_options'],
            'donor_is_options' => $this->CONFIGS['donor_is_options'],
            'comorbid_a' => [
                ['name' => 'acute_mi', 'label' => 'Acute MI'],
                ['name' => 'unstable_angina', 'label' => 'Unstable Angina'],
                ['name' => 'CAG', 'label' => 'CAG'],
                ['name' => 'PTCA', 'label' => 'PTCA'],
                ['name' => 'CABG', 'label' => 'CABG'],
                ['name' => 'CAD', 'label' => 'CAD'],
                ['name' => 'CVA', 'label' => 'CVA'],
                ['name' => 'stroke', 'label' => 'Stroke'],
                ['name' => 'PVD', 'label' => 'PVD'],
                ['name' => 'amputation', 'label' => 'Amputation'],
                ['name' => 'CHF', 'label' => 'CHF'],
                ['name' => 'heart_failure', 'label' => 'Heart failure'],
            ],
            'comorbid_b' => [
                ['name' => 'COPD', 'label' => 'COPD'],
                ['name' => 'asthma', 'label' => 'Asthma'],
                ['name' => 'TB', 'label' => 'TB'],
                ['name' => 'cirrhosis', 'label' => 'Cirrhosis'],
                ['name' => 'DLP', 'label' => 'DLP'],
                ['name' => 'PRCA', 'label' => 'PRCA'],
                ['name' => 'uric_greater_than_six', 'label' => 'Uric > 6'],
                ['name' => 'on_allopurinol', 'label' => 'On Allopurinol'],
                ['name' => 'gout', 'label' => 'Gout'],
                ['name' => 'hyperparathyroidism', 'label' => 'Hyperparathyroidism'],
                ['name' => 'PTH_grater_than_one_hundred', 'label' => 'PTH > 100'],
            ],
            'smoking_options' => $this->CONFIGS['smoking_options'],
            'smoking_types' => $this->CONFIGS['smoking_types'],
            'operative_data' => [
                ['name' => 'datetime_clamp_at_donor', 'label' => 'clamp time at donor'],
                ['name' => 'datetime_perfusion', 'label' => 'perfusion time'],
                ['name' => 'datetime_remove_from_ice', 'label' => 'remove from ice time'],
                ['name' => 'datetime_unclamp_all', 'label' => 'unclamp all time'],
            ],
            'crossmatches' => [
                ['name' => 'crossmatch_cdc', 'label' => 'CDC'],
                ['name' => 'crossmatch_cdc_ahg', 'label' => 'CDC-AHG'],
                ['name' => 'crossmatch_flow_cxm', 'label' => 'Flow-CXM'],
            ],
            'crossmatch_options' => $this->CONFIGS['crossmatch_options'],
            'graft_function_options' => $this->CONFIGS['graft_function_options'],
            'dialysis_mode_options' => $this->CONFIGS['dialysis_mode_options'],
            'dialysis_indication_fields' => [
                ['name' => 'delayed_graft_function_dialysis_indication_hyper_k', 'label' => 'Hyper K'],
                ['name' => 'delayed_graft_function_dialysis_indication_volume_overload', 'label' => 'Volume Overload'],
                ['name' => 'delayed_graft_function_dialysis_indication_uremia', 'label' => 'Uremia'],
            ],
            'graft_biopsy' => [
                'result' => [
                    'ATN' => false,
                    'ATI' => false,
                    'rejection' => false,
                    'TMA' => false,
                    'result_other' => null,
                ],
                'date_biopsy' => null,
                'attachment' => null,
            ],
            'biopsy_result_fields' => [
                ['name' => 'ATN', 'label' => 'ATN'],
                ['name' => 'ATI', 'label' => 'ATI'],
                ['name' => 'rejection', 'label' => 'Rejection'],
                ['name' => 'TMA', 'label' => 'TMA'],
            ],
            'complication_infection_fields' => [
                ['name' => 'UTI', 'label' => 'UTI'],
                ['name' => 'septicemia', 'label' => 'Septicemia'],
                ['name' => 'pneumonia', 'label' => 'Pneumonia'],
                ['name' => 'CMV', 'label' => 'CMV'],
                ['name' => 'herpez', 'label' => 'Herpez'],
                ['name' => 'BK', 'label' => 'BK'],
                ['name' => 'wound_infection', 'label' => 'Wound infection'],
            ],
            'complication_hematologic_fields' => [
                ['name' => 'anemia', 'label' => 'Anemia'],
                ['name' => 'thrombocytopenia', 'label' => 'Thrombocytopenia'],
                ['name' => 'leukopenia', 'label' => 'Leukopenia'],
                ['name' => 'prc_transfusion', 'label' => 'PRC transfusion'],
            ],
            'complication_vascular_fields' => [
                ['name' => 'stenosis_a', 'label' => 'Stenosis A'],
                ['name' => 'stenosis_v', 'label' => 'Stenosis V'],
                ['name' => 'thrombosis_a', 'label' => 'Thrombosis A'],
                ['name' => 'thrombosis_v', 'label' => 'Thrombosis V'],
            ],
            'complication_investigation_fields' => [
                ['name' => 'ultrasound', 'label' => 'Ultrasound'],
                ['name' => 'doppler', 'label' => 'Doppler'],
                ['name' => 'ct_abdomen', 'label' => 'CT abdomen'],
                ['name' => 'CTA', 'label' => 'CTA'],
                ['name' => 'CTV', 'label' => 'CTV'],
                ['name' => 'renogram', 'label' => 'renogram'],
            ],
            'complication_urological_fields' => [
                ['name' => 'ureter_stricture', 'label' => 'Ureter stricture'],
                ['name' => 'leakage', 'label' => 'Leakage'],
                ['name' => 'urinoma', 'label' => 'Urinoma'],
                ['name' => 'lymphocele', 'label' => 'Lymphocele'],
            ],
            'follow_up' => [
                'date_follow_up' => null,
                'place' => null,
                'for' => null,
                'md' => null,
            ],
            'indication_for_admission_infection_fields' => [
                ['name' => 'UTI', 'label' => 'UTI'],
                ['name' => 'pneumonia', 'label' => 'Pneumonia'],
                ['name' => 'viral_infection', 'label' => 'Viral infection'],
                ['name' => 'diarrhea', 'label' => 'Diarrhea'],
            ],
        ];
        $flash = $this->getFlash($caseRecord->title, $user);
        if ($caseRecord->meta['reason_for_admission'] === 'kt') {
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Follow Up', 'type' => '#', 'route' => '#follow_ups', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Complications', 'type' => '#', 'route' => '#complications', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Outcomes', 'type' => '#', 'route' => '#outcomes', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Operative Data', 'type' => '#', 'route' => '#operative-data', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Comorbidities', 'type' => '#', 'route' => '#comorbidities', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Clinical Data', 'type' => '#', 'route' => '#clinical-data', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Admission Data', 'type' => '#', 'route' => '#admission-data', 'can' => true]);
        } else {
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Follow Up', 'type' => '#', 'route' => '#follow_ups', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Diagnosis', 'type' => '#', 'route' => '#diagnosis', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Procedures', 'type' => '#', 'route' => '#procedures', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Indications', 'type' => '#', 'route' => '#indications', 'can' => true]);
            $flash['main-menu-links']->prepend(['icon' => 'slack-hash', 'label' => 'Admission Data', 'type' => '#', 'route' => '#admission-data', 'can' => true]);
        }
        $flash['action-menu'] = $this->getActionMenu(
            $caseRecord,
            $user,
            $caseRecord->status === 'draft'
                ? ['complete', 'destroy']
                : ['addendum', 'cancel']
        );
        $flash['hn'] = $caseRecord->patient->hn;
        $flash['breadcrumbs'] = $this->BREADCRUMBS;
        if (! $configs['can']['update']) {
            $flash['message'] = [
                'type' => 'warning',
                'title' => 'Autosave disabled.',
                'message' => 'Please Addendum to save changes.',
            ];
        }
        $configs['actions'] = $flash['action-menu'];

        if (in_array($caseRecord->status, ['completed', 'edited'])) {
            $caseRecord->actionLogs()->create([
                'actor_id' => $user->id,
                'action' => 'view',
            ]);
        }

        return [
            'formData' => $form,
            'formConfigs' => $configs,
            'flash' => $flash,
        ];
    }

    protected function getForm(ArrayObject $form): ArrayObject
    {
        /*check JSON schema due to change request*/

        // CR 2023-03-03 add maintain line check list
        if (! isset($form['maintain_drain_line'])) {
            $form['maintain_drain_line'] = false;
            $form['maintain_foley_line'] = false;
        }

        return $form;
    }
}
