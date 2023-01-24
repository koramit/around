<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord as CaseRecord;
use App\Models\Resources\Admission;
use App\Models\User;
use App\Rules\AnExists;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CaseRecordStoreAction extends KidneyTransplantAdmissionAction
{
    use CaseRecordFinishable;

    protected float $CRF_VERSION = 1.0;

    protected array $FORM_TEMPLATE = [
        'kt' => [
            'nephrologist' => null,
            'surgeon' => null,
            'date_off_drain' => null,
            'date_off_foley' => null,
            'insurance' => null,
            'cost' => null,
            'patient_transferred' => false,
            'patient_transferred_to' => null,
            'cause_of_esrd' => null,
            'donor_type' => null,
            'recipient_is' => null,
            'donor_is' => null,
            'blood_group_abo' => null,
            'blood_group_rh' => null,
            'hla_mismatch_a' => null,
            'hla_mismatch_b' => null,
            'hla_mismatch_dr' => null,
            'hla_mismatch_dq' => null,
            'pra_class_i_percent' => null,
            'pra_class_ii_percent' => null,
            'crossmatch_cdc' => null,
            'crossmatch_cdc_positive_specification' => null,
            'crossmatch_cdc_ahg' => null,
            'crossmatch_cdc_ahg_positive_specification' => null,
            'crossmatch_flow_cxm' => null,
            'crossmatch_flow_cxm_positive_specification' => null,
            'clinical_data_attachments' => [],
            'comorbidities' => [
                'none' => false,
                'acute_mi' => false,
                'date_acute_mi' => null,
                'unstable_angina' => false,
                'date_unstable_angina' => null,
                'CAG' => false,
                'date_CAG' => null,
                'PTCA' => false,
                'date_PTCA' => null,
                'CABG' => false,
                'date_CABG' => null,
                'CAD' => false,
                'date_CAD' => null,
                'CVA' => false,
                'date_CVA' => null,
                'stroke' => false,
                'date_stroke' => null,
                'PVD' => false,
                'date_PVD' => null,
                'amputation' => false,
                'date_amputation' => null,
                'CHF' => false,
                'date_CHF' => null,
                'heart_failure' => false,
                'date_heart_failure' => null,
                'HT' => false,
                'date_HT' => null,
                'on_HT_medication' => false,
                'date_start_HT_medication' => null,
                'HT_medication' => null,
                'DM' => false,
                'date_DM' => null,
                'on_DM_medication' => false,
                'date_start_DM_medication' => null,
                'DM_medication' => null,
                'COPD' => false,
                'date_COPD' => null,
                'asthma' => false,
                'date_asthma' => null,
                'TB' => false,
                'date_TB' => null,
                'cancer' => false,
                'date_cancer' => null,
                'cancer_type' => null,
                'cirrhosis' => false,
                'date_cirrhosis' => null,
                'DLP' => false,
                'date_DLP' => null,
                'PRCA' => false,
                'date_PRCA' => null,
                'uric_greater_than_six' => false,
                'date_uric_greater_than_six' => null,
                'on_allopurinol' => false,
                'date_start_allopurinol' => null,
                'gout' => false,
                'date_gout' => null,
                'hyperparathyroidism' => false,
                'date_hyperparathyroidism' => null,
                'PTH_grater_than_one_hundred' => false,
                'date_PTH_grater_than_one_hundred' => null,
                'smoking' => null,
                'date_start_smoking' => null,
                'comorbidities_other' => null,
            ],
            'datetime_harvest_start' => null,
            'datetime_harvest_finish' => null,
            'datetime_operation_start' => null,
            'datetime_operation_finish' => null,
            'cold_ischemic_time_hours' => null,
            'cold_ischemic_time_minutes' => null,
            'warm_ischemic_time_minutes' => null,
            'anastomosis_time_minutes' => null,
            'datetime_clamp_at_donor' => null,
            'datetime_perfusion' => null,
            'datetime_remove_from_ice' => null,
            'datetime_unclamp_all' => null,
            'operative_data_attachments' => [],
            'graft_function' => null,
            'delayed_graft_function_dialysis_mode' => null,
            'date_delayed_graft_function_dialysis_start' => null,
            'delayed_graft_function_dialysis_indication_hyper_k' => false,
            'delayed_graft_function_dialysis_indication_volume_overload' => false,
            'delayed_graft_function_dialysis_indication_uremia' => false,
            'delayed_graft_function_dialysis_indication_other' => null,
            'graft_function_graft_nephrectomy' => false,
            'graft_biopsies' => [],
            'complications' => [
                'none' => false,
                'UTI' => false,
                'septicemia' => false,
                'pneumonia' => false,
                'CMV' => false,
                'herpez' => false,
                'BK' => false,
                'wound_infection' => false,
                'infection_other' => null,
                'hematoma' => false,
                'blood_transfusion' => false,
                'blood_transfusion_unit' => null,
                'stenosis_a' => false,
                'stenosis_v' => false,
                'thrombosis_a' => false,
                'thrombosis_v' => false,
                'complication_other' => null,
                'ureter_stricture' => false,
                'leakage' => false,
                'urinoma' => false,
                'lymphocele' => false,
                'ultrasound' => false,
                'doppler' => false,
                'ct_abdomen' => false,
                'CTA' => false,
                'CTV' => false,
                'renogram' => false,
                'attachments' => [],
            ],
            'remarks' => null,
        ],
        'complication' => [

        ],
    ];

    public function __invoke(array $data, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'an' => ['required', 'digits:8', new AnExists],
            'reason_for_admission' => [
                'required',
                'string',
                Rule::in(collect($this->CONFIGS['admit_reasons'])->pluck('value')->toArray())
            ],
        ]);

        if ($caseRecord = CaseRecord::query()->where('meta->an', $validated['an'])->first()) {
            return ['key' => $caseRecord->hashed_key];
        }

        $caseRecord = new CaseRecord();
        $admission = Admission::query()->findByHashKey($validated['an'])->first();
        $caseRecord->patient_id = $admission->patient_id;
        $caseRecord->form = $this->FORM_TEMPLATE[$validated['reason_for_admission']];
        $caseRecord->meta = [
            'version' => $this->CRF_VERSION,
            'hn' => $admission->patient->hn,
            'an' => $admission->an,
            'name' => $admission->patient->first_name,
            'reason_for_admission' => $validated['reason_for_admission'],
        ];
        $caseRecord->save();
        $caseRecord->update(['meta->title' => $caseRecord->genTitle()]);
        $this->finishing($caseRecord, $caseRecord->patient, $user, $this->REGISTRY_ID);

        return ['key' => $caseRecord->hashed_key];
    }
}
