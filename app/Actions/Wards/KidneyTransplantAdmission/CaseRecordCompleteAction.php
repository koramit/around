<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use App\Rules\AcceptedIfOthersFalsy;
use App\Rules\FieldValueExists;
use App\Rules\FileExistsInStorage;
use App\Rules\SelectAtLeastOne;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CaseRecordCompleteAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('complete', $caseRecord)) {
            abort(403, 'You are not allowed to complete this case.');
        }

        // clean array data

        $validated = Validator::validate($data, [
            'nephrologist' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'surgeon' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'date_off_drain' => ['required', 'date'],
            'date_off_foley' => ['required', 'date'],
            'insurance' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'patient_transferred' => ['boolean'],
            'patient_transferred_to' => ['nullable','required_if:patient_transferred,true', new FieldValueExists('App\Models\Resources\Ward', 'name')],
            'cause_of_esrd' => ['required', 'string', 'max:255'],
            'donor_type' => ['required', Rule::in($this->CONFIGS['donor_types'])],
            'recipient_is' => ['nullable','required_if:donor_type,LD',
                Rule::in($caseRecord->patient->gender === 'male'
                    ? $this->CONFIGS['male_recipient_is_options']
                    : $this->CONFIGS['female_recipient_is_options'])
            ],
            'donor_is' => ['nullable','required_if:donor_type,LD', Rule::in($this->CONFIGS['donor_is_options'][$data['recipient_is']] ?? [])],
            'blood_group_abo' => ['required', Rule::in($this->CONFIGS['abo_options'])],
            'blood_group_rh' => ['required', Rule::in($this->CONFIGS['rh_options'])],
            'hla_mismatch_a' => ['nullable', Rule::in($this->CONFIGS['hla_mismatch_options'])],
            'hla_mismatch_b' => ['nullable', Rule::in($this->CONFIGS['hla_mismatch_options'])],
            'hla_mismatch_dr' => ['nullable', Rule::in($this->CONFIGS['hla_mismatch_options'])],
            'hla_mismatch_dq' => ['nullable', Rule::in($this->CONFIGS['hla_mismatch_options'])],
            'pra_class_i_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pra_class_ii_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'crossmatch_cdc' => ['nullable', Rule::in($this->CONFIGS['crossmatch_options'])],
            'crossmatch_cdc_positive_specification' => ['nullable', 'string', 'max:255'],
            'crossmatch_cdc_ahg' => ['nullable', Rule::in($this->CONFIGS['crossmatch_options'])],
            'crossmatch_cdc_ahg_positive_specification' => ['nullable', 'string', 'max:255'],
            'crossmatch_flow_cxm' => ['nullable', Rule::in($this->CONFIGS['crossmatch_options'])],
            'crossmatch_flow_cxm_positive_specification' => ['nullable', 'string', 'max:255'],
            'clinical_data_attachments' => ['array', 'min:1'],
            'clinical_data_attachments.*' => ['required', new FileExistsInStorage($this->CONFIGS['attachment_upload_pathname'])],
            'comorbidities' => ['array'],
            'comorbidities.none' => ['boolean', new AcceptedIfOthersFalsy($data['comorbidities'])],
            'comorbidities.acute_mi' => ['boolean'],
            'comorbidities.date_acute_mi' => ['nullable','required_if:comorbidities.acute_mi,true', 'date'],
            'comorbidities.unstable_angina' => ['boolean'],
            'comorbidities.date_unstable_angina' => ['nullable','required_if:comorbidities.unstable_angina,true', 'date'],
            'comorbidities.CAG' => ['boolean'],
            'comorbidities.date_CAG' => ['nullable','required_if:comorbidities.CAG,true', 'date'],
            'comorbidities.PTCA' => ['boolean'],
            'comorbidities.date_PTCA' => ['nullable','required_if:comorbidities.PTCA,true', 'date'],
            'comorbidities.CABG' => ['boolean'],
            'comorbidities.date_CABG' => ['nullable','required_if:comorbidities.CABG,true', 'date'],
            'comorbidities.CAD' => ['boolean'],
            'comorbidities.date_CAD' => ['nullable','required_if:comorbidities.CAD,true', 'date'],
            'comorbidities.CVA' => ['boolean'],
            'comorbidities.date_CVA' => ['nullable','required_if:comorbidities.CVA,true', 'date'],
            'comorbidities.stroke' => ['boolean'],
            'comorbidities.date_stroke' => ['nullable','required_if:comorbidities.stroke,true', 'date'],
            'comorbidities.PVD' => ['boolean'],
            'comorbidities.date_PVD' => ['nullable','required_if:comorbidities.PVD,true', 'date'],
            'comorbidities.amputation' => ['boolean'],
            'comorbidities.date_amputation' => ['nullable','required_if:comorbidities.amputation,true', 'date'],
            'comorbidities.CHF' => ['boolean'],
            'comorbidities.date_CHF' => ['nullable','required_if:comorbidities.CHF,true', 'date'],
            'comorbidities.heart_failure' => ['boolean'],
            'comorbidities.date_heart_failure' => ['nullable','required_if:comorbidities.heart_failure,true', 'date'],
            'comorbidities.HT' => ['boolean'],
            'comorbidities.date_HT' => ['nullable','required_if:comorbidities.HT,true', 'date'],
            'comorbidities.on_HT_medication' => ['boolean'],
            'comorbidities.date_start_HT_medication' => ['nullable','required_if:comorbidities.on_HT_medication,true', 'date'],
            'comorbidities.HT_medication' => ['nullable','required_if:comorbidities.on_HT_medication,true', 'string', 'max:255'],
            'comorbidities.DM' => ['boolean'],
            'comorbidities.date_DM' => ['nullable','required_if:comorbidities.DM,true', 'date'],
            'comorbidities.on_DM_medication' => ['boolean'],
            'comorbidities.date_start_DM_medication' => ['nullable','required_if:comorbidities.on_DM_medication,true', 'date'],
            'comorbidities.DM_medication' => ['nullable','required_if:comorbidities.on_DM_medication,true', 'string', 'max:255'],
            'comorbidities.COPD' => ['boolean'],
            'comorbidities.date_COPD' => ['nullable','required_if:comorbidities.COPD,true', 'date'],
            'comorbidities.asthma' => ['boolean'],
            'comorbidities.date_asthma' => ['nullable','required_if:comorbidities.asthma,true', 'date'],
            'comorbidities.TB' => ['boolean'],
            'comorbidities.date_TB' => ['nullable','required_if:comorbidities.TB,true', 'date'],
            'comorbidities.cancer' => ['boolean'],
            'comorbidities.date_cancer' => ['nullable','required_if:comorbidities.cancer,true', 'date'],
            'comorbidities.cancer_type' => ['nullable','required_if:comorbidities.cancer,true', 'string', 'max:255'],
            'comorbidities.cirrhosis' => ['boolean'],
            'comorbidities.date_cirrhosis' => ['nullable','required_if:comorbidities.cirrhosis,true', 'date'],
            'comorbidities.DLP' => ['boolean'],
            'comorbidities.date_DLP' => ['nullable','required_if:comorbidities.DLP,true', 'date'],
            'comorbidities.PRCA' => ['boolean'],
            'comorbidities.date_PRCA' => ['nullable','required_if:comorbidities.PRCA,true', 'date'],
            'comorbidities.uric_greater_than_six' => ['boolean'],
            'comorbidities.date_uric_greater_than_six' => ['nullable','required_if:comorbidities.uric_greater_than_six,true', 'date'],
            'comorbidities.on_allopurinol' => ['boolean'],
            'comorbidities.date_start_allopurinol' => ['nullable','required_if:comorbidities.on_allopurinol,true', 'date'],
            'comorbidities.gout' => ['boolean'],
            'comorbidities.date_gout' => ['nullable','required_if:comorbidities.gout,true', 'date'],
            'comorbidities.hyperparathyroidism' => ['boolean'],
            'comorbidities.date_hyperparathyroidism' => ['nullable','required_if:comorbidities.hyperparathyroidism,true', 'date'],
            'comorbidities.PTH_grater_than_one_hundred' => ['boolean'],
            'comorbidities.date_PTH_grater_than_one_hundred' => ['nullable','required_if:comorbidities.PTH_grater_than_one_hundred,true', 'date'],
            'comorbidities.smoking' => ['nullable', Rule::in($this->CONFIGS['smoking_options'])],
            'comorbidities.date_start_smoking' => ['nullable','required_if:comorbidities.smoking,smoker,ex-smoker', 'date'],
            'comorbidities.comorbidities_other' => ['nullable', 'string', 'max:255'],
            'datetime_harvest_start' => ['nullable','required_if:donor_type,CD', 'date'],
            'datetime_harvest_finish' => ['nullable','required_if:donor_type,CD', 'date', 'gt:datetime_harvest_start'],
            'datetime_operation_start' => ['required', 'date'],
            'datetime_operation_finish' => ['required', 'date', 'gt:datetime_operation_start'],
            'cold_ischemic_time_hours' => ['nullable', 'integer'],
            'cold_ischemic_time_minutes' => ['nullable', 'integer'],
            'warm_ischemic_time_minutes' => ['nullable', 'integer'],
            'anastomosis_time_minutes' => ['required', 'integer'],
            'datetime_clamp_at_donor' => ['nullable', 'date'],
            'datetime_perfusion' => ['nullable', 'date'],
            'datetime_remove_from_ice' => ['nullable', 'date'],
            'datetime_unclamp_all' => ['nullable', 'date'],
            'operative_data_attachments' => ['array', 'min:1'],
            'operative_data_attachments.*' => ['required', new FileExistsInStorage($this->CONFIGS['attachment_upload_pathname'])],
            'graft_function' => ['required', Rule::in($this->CONFIGS['graft_function_options'])],
            'delayed_graft_function_dialysis_mode' => ['required_if:graft_function,delayed graft function', Rule::in($this->CONFIGS['dialysis_mode_options'])],
            'date_delayed_graft_function_dialysis_start' => ['required_if:graft_function,delayed graft function', 'date'],
            'delayed_graft_function_dialysis_indication_hyper_k' => ['boolean'],
            'delayed_graft_function_dialysis_indication_volume_overload' => ['boolean'],
            'delayed_graft_function_dialysis_indication_uremia' => ['boolean'],
            'delayed_graft_function_dialysis_indication_other' => ['nullable', 'string', 'max:255'],
            'graft_function_graft_nephrectomy' => ['boolean'],
            'graft_biopsies' => ['array'],
            'graft_biopsies.*.result' => ['array', new SelectAtLeastOne()],
            'graft_biopsies.*.result.ATN' => ['boolean'],
            'graft_biopsies.*.result.ATI' => ['boolean'],
            'graft_biopsies.*.result.rejection' => ['boolean'],
            'graft_biopsies.*.result.TMA' => ['boolean'],
            'graft_biopsies.*.result.result_other' => ['nullable', 'string', 'max:255'],
            'graft_biopsies.*.date_biopsy' => ['required', 'date'],
            'graft_biopsies.*.attachment' => ['nullable', new FileExistsInStorage($this->CONFIGS['attachment_upload_pathname'])],
            'complications' => ['array'],
            'complications.none' => ['boolean', new AcceptedIfOthersFalsy($data['complications'])],
            'complications.UTI' => ['boolean'],
            'complications.septicemia' => ['boolean'],
            'complications.pneumonia' => ['boolean'],
            'complications.CMV' => ['boolean'],
            'complications.herpez' => ['boolean'],
            'complications.BK' => ['boolean'],
            'complications.wound_infection' => ['boolean'],
            'complications.infection_other' => ['nullable', 'string', 'max:255'],
            'complications.hematoma' => ['boolean'],
            'complications.blood_transfusion' => ['boolean'],
            'complications.blood_transfusion_unit' => ['nullable','required_if:complications.blood_transfusion,true', 'integer'],
            'complications.stenosis_a' => ['boolean'],
            'complications.stenosis_v' => ['boolean'],
            'complications.thrombosis_a' => ['boolean'],
            'complications.thrombosis_v' => ['boolean'],
            'complications.complication_other' => ['nullable', 'string', 'max:255'],
            'complications.ureter_stricture' => ['boolean'],
            'complications.leakage' => ['boolean'],
            'complications.urinoma' => ['boolean'],
            'complications.lymphocele' => ['boolean'],
            'complications.ultrasound' => ['boolean'],
            'complications.doppler' => ['boolean'],
            'complications.ct_abdomen' => ['boolean'],
            'complications.CTA' => ['boolean'],
            'complications.CTV' => ['boolean'],
            'complications.renogram' => ['boolean'],
            'complications.attachments' => ['array'],
            'complications.attachments.*' => ['required', new FileExistsInStorage($this->CONFIGS['attachment_upload_pathname'])],
            'remarks' => ['nullable', 'string', 'max:1024'],
        ]);

        /*$caseRecord->update(['status' => 'completed']);

        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'complete',
        ]);*/

        return [
                'type' => 'success',
                'title' => 'Case completed successfully.',
                'message' => $caseRecord->title,
        ];
    }

    protected function resetConditionalData(&$data)
    {
        // patient transferred
        if ($data['patient_transferred'] === true) {
            $data['patient_transferred_to'] = null;
        }

        // donor type
        if ($data['donor_type'] === 'CD') {
            $data['recipient_is'] = null;
            $data['donor_is'] = null;
        } elseif ($data['donor_type'] === 'LD') {
            $data['datetime_harvest_start'] = null;
            $data['datetime_harvest_finish'] = null;
        }

        // no comorbidities
        if ($data['comorbidities']['none'] === true) {
            $data['comorbidities']['acute_mi'] = false;
                $data['comorbidities']['date_acute_mi'] = null;
                $data['comorbidities']['unstable_angina'] = false;
                $data['comorbidities']['date_unstable_angina'] = null;
                $data['comorbidities']['CAG'] = false;
                $data['comorbidities']['date_CAG'] = null;
                $data['comorbidities']['PTCA'] = false;
                $data['comorbidities']['date_PTCA'] = null;
                $data['comorbidities']['CABG'] = false;
                $data['comorbidities']['date_CABG'] = null;
                $data['comorbidities']['CAD'] = false;
                $data['comorbidities']['date_CAD'] = null;
                $data['comorbidities']['CVA'] = false;
                $data['comorbidities']['date_CVA'] = null;
                $data['comorbidities']['stroke'] = false;
                $data['comorbidities']['date_stroke'] = null;
                $data['comorbidities']['PVD'] = false;
                $data['comorbidities']['date_PVD'] = null;
                $data['comorbidities']['amputation'] = false;
                $data['comorbidities']['date_amputation'] = null;
                $data['comorbidities']['CHF'] = false;
                $data['comorbidities']['date_CHF'] = null;
                $data['comorbidities']['heart_failure'] = false;
                $data['comorbidities']['date_heart_failure'] = null;
                $data['comorbidities']['HT'] = false;
                $data['comorbidities']['date_HT'] = null;
                $data['comorbidities']['on_HT_medication'] = false;
                $data['comorbidities']['date_start_HT_medication'] = null;
                $data['comorbidities']['HT_medication'] = null;
                $data['comorbidities']['DM'] = false;
                $data['comorbidities']['date_DM'] = null;
                $data['comorbidities']['on_DM_medication'] = false;
                $data['comorbidities']['date_start_DM_medication'] = null;
                $data['comorbidities']['DM_medication'] = null;
                $data['comorbidities']['COPD'] = false;
                $data['comorbidities']['date_COPD'] = null;
                $data['comorbidities']['asthma'] = false;
                $data['comorbidities']['date_asthma'] = null;
                $data['comorbidities']['TB'] = false;
                $data['comorbidities']['date_TB'] = null;
                $data['comorbidities']['cancer'] = false;
                $data['comorbidities']['date_cancer'] = null;
                $data['comorbidities']['cancer_type'] = null;
                $data['comorbidities']['cirrhosis'] = false;
                $data['comorbidities']['date_cirrhosis'] = null;
                $data['comorbidities']['DLP'] = false;
                $data['comorbidities']['date_DLP'] = null;
                $data['comorbidities']['PRCA'] = false;
                $data['comorbidities']['date_PRCA'] = null;
                $data['comorbidities']['uric_greater_than_six'] = false;
                $data['comorbidities']['date_uric_greater_than_six'] = null;
                $data['comorbidities']['on_allopurinol'] = false;
                $data['comorbidities']['date_start_allopurinol'] = null;
                $data['comorbidities']['gout'] = false;
                $data['comorbidities']['date_gout'] = null;
                $data['comorbidities']['hyperparathyroidism'] = false;
                $data['comorbidities']['date_hyperparathyroidism'] = null;
                $data['comorbidities']['PTH_grater_than_one_hundred'] = false;
                $data['comorbidities']['date_PTH_grater_than_one_hundred'] = null;
                $data['comorbidities']['smoking'] = null;
                $data['comorbidities']['date_start_smoking'] = null;
                $data['comorbidities']['comorbidities_other'] = null;
        }

        // graft function
        if (
            $data['graft_function'] === 'immediate graft function'
            || $data['graft_function'] === 'slow graft function'
        ) {
            $data['delayed_graft_function_dialysis_mode'] = null;
            $data['date_delayed_graft_function_dialysis_start'] = null;
            $data['delayed_graft_function_dialysis_indication_hyper_k'] = false;
            $data['delayed_graft_function_dialysis_indication_volume_overload'] = false;
            $data['delayed_graft_function_dialysis_indication_uremia'] = false;
            $data['delayed_graft_function_dialysis_indication_other'] = null;
            $data['graft_function_graft_nephrectomy'] = false;
        } elseif ($data['graft_function'] === 'delayed graft function') {
            $data['graft_function_graft_nephrectomy'] = false;
        } elseif ($data['graft_function'] === 'primary non-function') {
            $data['delayed_graft_function_dialysis_mode'] = null;
            $data['date_delayed_graft_function_dialysis_start'] = null;
            $data['delayed_graft_function_dialysis_indication_hyper_k'] = false;
            $data['delayed_graft_function_dialysis_indication_volume_overload'] = false;
            $data['delayed_graft_function_dialysis_indication_uremia'] = false;
            $data['delayed_graft_function_dialysis_indication_other'] = null;
        }

        // complications
        if ($data['complications']['none'] === true) {
            $data['complications']['UTI'] = false;
            $data['complications']['septicemia'] = false;
            $data['complications']['pneumonia'] = false;
            $data['complications']['CMV'] = false;
            $data['complications']['herpez'] = false;
            $data['complications']['BK'] = false;
            $data['complications']['wound_infection'] = false;
            $data['complications']['infection_other'] = null;
            $data['complications']['hematoma'] = false;
            $data['complications']['blood_transfusion'] = false;
            $data['complications']['blood_transfusion_unit'] = null;
            $data['complications']['stenosis_a'] = false;
            $data['complications']['stenosis_v'] = false;
            $data['complications']['thrombosis_a'] = false;
            $data['complications']['thrombosis_v'] = false;
            $data['complications']['complication_other'] = null;
            $data['complications']['ureter_stricture'] = false;
            $data['complications']['leakage'] = false;
            $data['complications']['urinoma'] = false;
            $data['complications']['lymphocele'] = false;
            $data['complications']['ultrasound'] = false;
            $data['complications']['doppler'] = false;
            $data['complications']['ct_abdomen'] = false;
            $data['complications']['CTA'] = false;
            $data['complications']['CTV'] = false;
            $data['complications']['renogram'] = false;
        }

        // attachments






    }
}
