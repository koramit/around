<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\Resources\Admission;
use App\Models\User;
use App\Rules\AcceptedIfOthersFalsy;
use App\Rules\FieldValueExists;
use App\Rules\FileExistsInStorage;
use App\Rules\SelectAtLeastOne;
use App\Traits\ChangesComparable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CaseRecordCompleteAction extends KidneyTransplantAdmissionAction
{
    use ChangesComparable;

    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        if (in_array($caseRecord->status, ['completed', 'edited'])) {
            if ($user->cannot('addendum', $caseRecord)) {
                abort(403, 'You are not allowed to complete this addendum.');
            }
        } else {
            if ($user->cannot('update', $caseRecord)) {
                abort(403, 'You are not allowed to complete this case.');
            }
        }

        $admission = Admission::query()->findByHashKey($caseRecord->meta['an'])->first();
        $data['datetime_admission'] = $admission->encountered_at->tz(7)->format('Y-m-d H:i:s');

        $validated = $caseRecord->meta['reason_for_admission'] === 'kt'
            ? $this->validateAdmitForTransplant($data, $caseRecord)
            : $this->validateAdmitForComplication($data);

        if ($caseRecord->status === 'completed') {
            return $this->addendum($caseRecord, $validated, $user->id);
        }

        $caseRecord->update([
            'form' => $validated,
            'status' => 'completed',
        ]);

        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'complete',
        ]);

        return [
            'type' => 'success',
            'title' => 'Case completed successfully.',
            'message' => $caseRecord->title,
        ];
    }

    protected function addendum(KidneyTransplantAdmissionCaseRecord $caseRecord, array $validated, int $userId): array
    {
        $diff = $this->formJsonDiff($caseRecord->form, $validated);

        if (! count($diff)) {
            return [
                'type' => 'warning',
                'title' => 'No updates.',
                'message' => $caseRecord->title,
            ];
        }

        $caseRecord->update([
            'form' => $validated,
            'status' => 'edited',
        ]);

        $caseRecord->actionLogs()->create([
            'action' => 'change',
            'actor_id' => $userId,
            'payload' => $diff,
        ]);

        return [
            'type' => 'success',
            'title' => 'Case addended successfully.',
            'message' => $caseRecord->title,
        ];
    }

    protected function validateAdmitForTransplant(array &$data, KidneyTransplantAdmissionCaseRecord $caseRecord): array
    {
        $this->resetConditionalData($data);
        if ($data['graft_function'] === 'delayed graft function') {
            $data['delayed_graft_function_dialysis_indication'] = [
                'hyper_k' => $data['delayed_graft_function_dialysis_indication_hyper_k'],
                'volume_overload' => $data['delayed_graft_function_dialysis_indication_volume_overload'],
                'uremia' => $data['delayed_graft_function_dialysis_indication_uremia'],
                'other' => $data['delayed_graft_function_dialysis_indication_other'],
            ];
        }

        return Validator::validate($data, [
            'nephrologist' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'surgeon' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'date_off_drain' => ['required', 'date', 'after:datetime_operation_finish'],
            'date_off_foley' => ['required', 'date', 'after:datetime_operation_finish'],
            'insurance' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'tel_no' => ['required', 'string', 'min:8', 'max:30'],
            'alternative_contact' => ['required', 'string', 'min:12', 'max:255'],
            'contact_info_confirmed' => ['boolean', 'accepted'],
            'patient_transferred' => ['boolean'],
            'patient_transferred_to' => ['nullable', 'required_if:patient_transferred,true', new FieldValueExists('App\Models\Resources\Ward', 'name')],
            'cause_of_esrd' => ['required', 'string', 'max:255'],
            'donor_type' => ['required', Rule::in($this->CONFIGS['donor_types'])],
            'recipient_is' => ['nullable', 'required_if:donor_type,LD',
                Rule::in($caseRecord->patient->gender === 'male'
                    ? $this->CONFIGS['male_recipient_is_options']
                    : $this->CONFIGS['female_recipient_is_options']),
            ],
            'donor_is' => ['nullable', 'required_if:donor_type,LD', Rule::in($this->CONFIGS['donor_is_options'][$data['recipient_is']] ?? [])],
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
            'clinical_data_attachments.*' => ['required', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'comorbidities' => ['array'],
            'comorbidities.none' => ['boolean', new AcceptedIfOthersFalsy($data['comorbidities'])],
            'comorbidities.acute_mi' => ['boolean'],
            'comorbidities.date_acute_mi' => ['nullable', 'required_if:comorbidities.acute_mi,true', 'date', 'before:datetime_admission'],
            'comorbidities.unstable_angina' => ['boolean'],
            'comorbidities.date_unstable_angina' => ['nullable', 'required_if:comorbidities.unstable_angina,true', 'date', 'before:datetime_admission'],
            'comorbidities.CAG' => ['boolean'],
            'comorbidities.date_CAG' => ['nullable', 'required_if:comorbidities.CAG,true', 'date', 'before:datetime_admission'],
            'comorbidities.PTCA' => ['boolean'],
            'comorbidities.date_PTCA' => ['nullable', 'required_if:comorbidities.PTCA,true', 'date', 'before:datetime_admission'],
            'comorbidities.CABG' => ['boolean'],
            'comorbidities.date_CABG' => ['nullable', 'required_if:comorbidities.CABG,true', 'date', 'before:datetime_admission'],
            'comorbidities.CAD' => ['boolean'],
            'comorbidities.date_CAD' => ['nullable', 'required_if:comorbidities.CAD,true', 'date', 'before:datetime_admission'],
            'comorbidities.CVA' => ['boolean'],
            'comorbidities.date_CVA' => ['nullable', 'required_if:comorbidities.CVA,true', 'date', 'before:datetime_admission'],
            'comorbidities.stroke' => ['boolean'],
            'comorbidities.date_stroke' => ['nullable', 'required_if:comorbidities.stroke,true', 'date', 'before:datetime_admission'],
            'comorbidities.PVD' => ['boolean'],
            'comorbidities.date_PVD' => ['nullable', 'required_if:comorbidities.PVD,true', 'date', 'before:datetime_admission'],
            'comorbidities.amputation' => ['boolean'],
            'comorbidities.date_amputation' => ['nullable', 'required_if:comorbidities.amputation,true', 'date', 'before:datetime_admission'],
            'comorbidities.CHF' => ['boolean'],
            'comorbidities.date_CHF' => ['nullable', 'required_if:comorbidities.CHF,true', 'date', 'before:datetime_admission'],
            'comorbidities.heart_failure' => ['boolean'],
            'comorbidities.date_heart_failure' => ['nullable', 'required_if:comorbidities.heart_failure,true', 'date', 'before:datetime_admission'],
            'comorbidities.HT' => ['boolean'],
            'comorbidities.date_HT' => ['nullable', 'required_if:comorbidities.HT,true', 'date', 'before:datetime_admission'],
            'comorbidities.on_HT_medication' => ['boolean'],
            'comorbidities.date_start_HT_medication' => ['nullable', 'required_if:comorbidities.on_HT_medication,true', 'date', 'before:datetime_admission'],
            'comorbidities.HT_medication' => ['nullable', 'required_if:comorbidities.on_HT_medication,true', 'string', 'max:255'],
            'comorbidities.DM' => ['boolean'],
            'comorbidities.date_DM' => ['nullable', 'required_if:comorbidities.DM,true', 'date', 'before:datetime_admission'],
            'comorbidities.on_DM_medication' => ['boolean'],
            'comorbidities.date_start_DM_medication' => ['nullable', 'required_if:comorbidities.on_DM_medication,true', 'date', 'before:datetime_admission'],
            'comorbidities.DM_medication' => ['nullable', 'required_if:comorbidities.on_DM_medication,true', 'string', 'max:255'],
            'comorbidities.COPD' => ['boolean'],
            'comorbidities.date_COPD' => ['nullable', 'required_if:comorbidities.COPD,true', 'date', 'before:datetime_admission'],
            'comorbidities.asthma' => ['boolean'],
            'comorbidities.date_asthma' => ['nullable', 'required_if:comorbidities.asthma,true', 'date', 'before:datetime_admission'],
            'comorbidities.TB' => ['boolean'],
            'comorbidities.date_TB' => ['nullable', 'required_if:comorbidities.TB,true', 'date', 'before:datetime_admission'],
            'comorbidities.cancer' => ['boolean'],
            'comorbidities.date_cancer' => ['nullable', 'required_if:comorbidities.cancer,true', 'date', 'before:datetime_admission'],
            'comorbidities.cancer_type' => ['nullable', 'required_if:comorbidities.cancer,true', 'string', 'max:255'],
            'comorbidities.cirrhosis' => ['boolean'],
            'comorbidities.date_cirrhosis' => ['nullable', 'required_if:comorbidities.cirrhosis,true', 'date', 'before:datetime_admission'],
            'comorbidities.DLP' => ['boolean'],
            'comorbidities.date_DLP' => ['nullable', 'required_if:comorbidities.DLP,true', 'date', 'before:datetime_admission'],
            'comorbidities.PRCA' => ['boolean'],
            'comorbidities.date_PRCA' => ['nullable', 'required_if:comorbidities.PRCA,true', 'date', 'before:datetime_admission'],
            'comorbidities.uric_greater_than_six' => ['boolean'],
            'comorbidities.date_uric_greater_than_six' => ['nullable', 'required_if:comorbidities.uric_greater_than_six,true', 'date', 'before:datetime_admission'],
            'comorbidities.on_allopurinol' => ['boolean'],
            'comorbidities.date_start_allopurinol' => ['nullable', 'required_if:comorbidities.on_allopurinol,true', 'date', 'before:datetime_admission'],
            'comorbidities.gout' => ['boolean'],
            'comorbidities.date_gout' => ['nullable', 'required_if:comorbidities.gout,true', 'date', 'before:datetime_admission'],
            'comorbidities.hyperparathyroidism' => ['boolean'],
            'comorbidities.date_hyperparathyroidism' => ['nullable', 'required_if:comorbidities.hyperparathyroidism,true', 'date', 'before:datetime_admission'],
            'comorbidities.PTH_grater_than_one_hundred' => ['boolean'],
            'comorbidities.date_PTH_grater_than_one_hundred' => ['nullable', 'required_if:comorbidities.PTH_grater_than_one_hundred,true', 'date', 'before:datetime_admission'],
            'comorbidities.smoking' => ['boolean'],
            'comorbidities.date_start_smoking' => ['nullable', 'required_if:comorbidities.smoking,true', 'date', 'before:datetime_admission'],
            'comorbidities.smoking_type' => ['nullable', 'required_if:comorbidities.smoking,true', Rule::in($this->CONFIGS['smoking_types'])],
            'comorbidities.comorbidities_other' => ['nullable', 'string', 'max:255'],
            'datetime_harvest_start' => ['nullable', 'required_if:donor_type,CD', 'date'],
            'datetime_harvest_finish' => ['nullable', 'required_if:donor_type,CD', 'date', 'after:datetime_harvest_start'],
            'donor_cd_hospital' => ['nullable', 'required_if:donor_type,CD', 'string', 'max:255'],
            'datetime_operation_start' => ['required', 'date', 'after:datetime_admission'],
            'datetime_operation_finish' => ['required', 'date', 'after:datetime_operation_start'],
            'cold_ischemic_time_hours' => ['nullable', Rule::requiredIf(! $data['cold_ischemic_time_minutes']), 'integer', 'min:0', 'max:23'],
            'cold_ischemic_time_minutes' => ['nullable', Rule::requiredIf(! $data['cold_ischemic_time_hours']), 'integer', 'min:0', 'max:59'],
            'warm_ischemic_time_minutes' => ['nullable', 'integer', 'min:1', 'max:59'],
            'anastomosis_time_minutes' => ['required', 'integer', 'min:1', 'max:59'],
            'datetime_clamp_at_donor' => ['nullable', 'date'],
            'datetime_perfusion' => ['nullable', 'date'],
            'datetime_remove_from_ice' => ['nullable', 'date'],
            'datetime_unclamp_all' => ['nullable', 'date'],
            'operative_data_attachments' => ['array', 'min:1'],
            'operative_data_attachments.*' => ['required', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'graft_function' => ['required', Rule::in($this->CONFIGS['graft_function_options'])],
            'delayed_graft_function_dialysis_mode' => ['nullable', 'required_if:graft_function,delayed graft function', Rule::in($this->CONFIGS['dialysis_mode_options'])],
            'date_delayed_graft_function_dialysis_start' => ['nullable', 'required_if:graft_function,delayed graft function', 'date'],
            'delayed_graft_function_dialysis_indication_hyper_k' => ['boolean'],
            'delayed_graft_function_dialysis_indication_volume_overload' => ['boolean'],
            'delayed_graft_function_dialysis_indication_uremia' => ['boolean'],
            'delayed_graft_function_dialysis_indication_other' => ['nullable', 'string', 'max:255'],
            'delayed_graft_function_dialysis_indication' => ['sometimes', new SelectAtLeastOne()],
            'graft_function_graft_nephrectomy' => ['boolean'],
            'graft_biopsies' => ['array'],
            'graft_biopsies.*.result' => ['array', new SelectAtLeastOne()],
            'graft_biopsies.*.result.ATN' => ['boolean'],
            'graft_biopsies.*.result.ATI' => ['boolean'],
            'graft_biopsies.*.result.rejection' => ['boolean'],
            'graft_biopsies.*.result.TMA' => ['boolean'],
            'graft_biopsies.*.result.result_other' => ['nullable', 'string', 'max:255'],
            'graft_biopsies.*.date_biopsy' => ['required', 'date', 'after:datetime_admission'],
            'graft_biopsies.*.attachment' => ['nullable', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
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
            'complications.blood_transfusion_unit' => ['nullable', 'required_if:complications.blood_transfusion,true', 'integer'],
            'complications.anemia' => ['boolean'],
            'complications.thrombocytopenia' => ['boolean'],
            'complications.leukopenia' => ['boolean'],
            'complications.prc_transfusion' => ['boolean'],
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
            'complications.attachments.*' => ['required', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'follow_ups' => ['array'],
            'follow_ups.*.date_follow_up' => ['required', 'date', 'after:datetime_admission'],
            'follow_ups.*.place' => ['required', 'string', 'max:255'],
            'follow_ups.*.for' => ['required', 'string', 'max:255'],
            'follow_ups.*.md' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1024'],
        ], [
            'graft_biopsies.*.date_biopsy.required' => 'The date biopsy #:position is required.',
            'graft_biopsies.*.date_biopsy.after' => 'The date biopsy #:position must be a date after admission date.',
            'follow_ups.*.date_follow_up.required' => 'The date follow up #:position is required.',
            'follow_ups.*.date_follow_up.after' => 'The date follow up #:position must be a date after admission date.',
            'follow_ups.*.place.required' => 'The place follow up #:position is required.',
            'follow_ups.*.for.required' => 'The for follow up #:position is required.',
        ]);
    }

    protected function validateAdmitForComplication(array &$data): array
    {
        if ($data['patient_transferred'] !== true) {
            $data['patient_transferred_to'] = null;
        }

        if ($data['complications']['metabolic_disturbance'] !== true) {
            $data['complications']['metabolic_disturbance_specification'] = null;
        }

        if ($data['complications']['surgical_complication'] !== true) {
            $data['complications']['surgical_complication_specification'] = null;
        }

        if ($data['imaging'] !== true) {
            $data['imaging_specification'] = null;
        }

        // rearrange attachments
        $temp = [];
        foreach ($data['complications']['attachments'] as $attachment) {
            if ($attachment) {
                $temp[] = $attachment;
            }
        }
        $data['complications']['attachments'] = $temp;
        $temp = [];
        foreach ($data['procedure_data_attachments'] as $attachment) {
            if ($attachment) {
                $temp[] = $attachment;
            }
        }
        $data['procedure_data_attachments'] = $temp;

        return Validator::validate($data, [
            'nephrologist' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'surgeon' => ['required', new FieldValueExists('App\Models\Resources\Person', 'name')],
            'insurance' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'numeric', 'min:0'],
            'tel_no' => ['required', 'string', 'min:8', 'max:30'],
            'alternative_contact' => ['required', 'string', 'min:12', 'max:255'],
            'contact_info_confirmed' => ['boolean', 'accepted'],
            'patient_transferred' => ['boolean'],
            'patient_transferred_to' => ['nullable', 'required_if:patient_transferred,true', new FieldValueExists('App\Models\Resources\Ward', 'name')],
            'complications' => ['array', new SelectAtLeastOne()],
            'complications.AKI' => ['boolean'],
            'complications.metabolic_disturbance' => ['boolean'],
            'complications.metabolic_disturbance_specification' => ['nullable', 'required_if:complications.metabolic_disturbance,true', 'string', 'max:255'],
            'complications.UTI' => ['boolean'],
            'complications.pneumonia' => ['boolean'],
            'complications.viral_infection' => ['boolean'],
            'complications.diarrhea' => ['boolean'],
            'complications.infection_other' => ['nullable', 'string', 'max:255'],
            'complications.desensitization_protocol' => ['boolean'],
            'complications.surgical_complication' => ['boolean'],
            'complications.surgical_complication_specification' => ['nullable', 'required_if:complications.surgical_complication,true', 'string', 'max:255'],
            'complications.treatment_rejection' => ['boolean'],
            'complications.complications_other' => ['nullable', 'string', 'max:255'],
            'complications.attachments' => ['array'],
            'complications.attachments.*' => ['required', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'angioplasty' => ['boolean'],
            'imaging' => ['boolean'],
            'imaging_specification' =>  ['nullable', 'required_if:imaging,true', 'string', 'max:255'],
            'graft_biopsies' => ['array'],
            'graft_biopsies.*.result' => ['array', new SelectAtLeastOne()],
            'graft_biopsies.*.result.ATN' => ['boolean'],
            'graft_biopsies.*.result.ATI' => ['boolean'],
            'graft_biopsies.*.result.rejection' => ['boolean'],
            'graft_biopsies.*.result.TMA' => ['boolean'],
            'graft_biopsies.*.result.result_other' => ['nullable', 'string', 'max:255'],
            'graft_biopsies.*.date_biopsy' => ['required', 'date', 'after:datetime_admission'],
            'graft_biopsies.*.attachment' => ['nullable', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'procedure_data_attachments' => ['array'],
            'procedure_data_attachments.*' => ['required', new FileExistsInStorage('uploads/'.$this->CONFIGS['attachment_upload_pathname'])],
            'final_diagnosis' => ['required', 'string', 'max:255'],
            'follow_ups' => ['array'],
            'follow_ups.*.date_follow_up' => ['required', 'date', 'after:datetime_admission'],
            'follow_ups.*.place' => ['required', 'string', 'max:255'],
            'follow_ups.*.for' => ['required', 'string', 'max:255'],
            'follow_ups.*.md' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:1024'],
        ], [
            'graft_biopsies.*.date_biopsy.required' => 'The date biopsy #:position is required.',
            'graft_biopsies.*.date_biopsy.after' => 'The date biopsy #:position must be a date after admission date.',
            'follow_ups.*.date_follow_up.required' => 'The date follow up #:position is required.',
            'follow_ups.*.date_follow_up.after' => 'The date follow up #:position must be a date after admission date.',
            'follow_ups.*.place.required' => 'The place follow up #:position is required.',
            'follow_ups.*.for.required' => 'The for follow up #:position is required.',
        ]);
    }

    protected function resetConditionalData(&$data)
    {
        if ($data['patient_transferred'] !== true) {
            $data['patient_transferred_to'] = null;
        }

        if ($data['donor_type'] === 'CD') {
            $data['recipient_is'] = null;
            $data['donor_is'] = null;
        } elseif ($data['donor_type'] === 'LD') {
            $data['datetime_harvest_start'] = null;
            $data['datetime_harvest_finish'] = null;
            $data['donor_cd_hospital'] = null;
        }

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
            $data['complications']['anemia'] = false;
            $data['complications']['thrombocytopenia'] = false;
            $data['complications']['leukopenia'] = false;
            $data['complications']['prc_transfusion'] = false;
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

        // @TODO delete attachments if needed
        // attachments
        $fields = ['clinical_data_attachments', 'operative_data_attachments'];
        foreach ($fields as $field) {
            $temp = [];
            foreach ($data[$field] as $attachment) {
                if ($attachment) {
                    $temp[] = $attachment;
                }
            }
            $data[$field] = $temp;
        }

        $temp = [];
        foreach ($data['complications']['attachments'] as $attachment) {
            if ($attachment) {
                $temp[] = $attachment;
            }
        }
        $data['complications']['attachments'] = $temp;
    }
}
