<?php

namespace App\Actions\Clinics\PostKT;

use App\APIs\PortalAPI;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Managers\Resources\AdmissionManager;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Models\User;
use App\Rules\HnExists;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CaseStoreAction extends CaseBaseAction
{
    use CaseRecordFinishable;

    protected float $CRF_VERSION = 1.0;

    protected array $FORM_TEMPLATE = [
        'nephrologist' => null,
        'surgeon' => null,
        'donor_hn' => null,
        'donor_name' => null,
        'donor_is' => null, // LD
        'donor_gender' => null,
        'donor_age' => null,
        'donor_hospital' => null, // CD
        'donor_trauma' => false, // CD
        'donor_cause_of_death' => null, // CD
        'co_recipient_hospital' => null, // CD
        'preemptive' => false,
        'abo_incompatible' => false,
        'combined_with_liver' => false,
        'combined_with_heart' => false,
        'combined_with_pancreas' => false,
        'kt_times' => null,
        'medical_scheme' => null,

        'pre_kt_prc_unit' => null,
        'anastomosis_time_minutes' => null,
        'warm_ischemic_time_minutes' => null,
        'cold_ischemic_time_hours' => null,
        'cold_ischemic_time_minutes' => null,
        'time_clamp_at_donor' => null,
        'graft_function' => null,

        'cause_of_esrd' => null,
        'native_biopsy_report' => null,
        'date_first_rrt' => null,
        'rrt_mode' => null,
        'gestation_g' => null,
        'gestation_p' => null,
        'gestation_a' => null,
        'baseline_cr' => null,
        'pre_kt_cr' => null,
        'crossmatch_cdc' => null,
        'crossmatch_cdc_positive_specification' => null,
        'crossmatch_cdc_ahg' => null,
        'crossmatch_cdc_ahg_positive_specification' => null,
        'crossmatch_flow_cxm' => null,
        'crossmatch_flow_cxm_positive_specification' => null,
        'last_pra_class_i_percent' => null,
        'date_last_pra_class_i' => null,
        'last_pra_class_ii_percent' => null,
        'date_last_pra_class_ii' => null,
        'peak_pra_class_i_percent' => null,
        'date_peak_pra_class_i' => null,
        'peak_pra_class_ii_percent' => null,
        'date_peak_pra_class_ii' => null,
        'mismatch_a' => null,
        'mismatch_b' => null,
        'mismatch_dr' => null,
        'mismatch_cw' => null,
        'mismatch_drb' => null,
        'mismatch_dqb1' => null,
        'mismatch_dpb1' => null,
        'mismatch_mica' => null,
        'mismatch_dqa1' => null,
        'mismatch_dpa1' => null,
        'recipient_cmv_igg' => null,
        'donor_cmv_igg' => null,

        'refer' => null,
        'graft_status' => 'graft function',
        'date_update_graft_status' => null,
        'date_graft_loss' => null,
        'graft_loss_codes' => [],
        'dialysis_status' => null,
        'graft_loss_status_note' => null,
        'date_latest_cr' => null,
        'latest_cr' => null,
        'patient_status' => 'alive',
        'date_update_patient_status' => null,
        'date_dead' => null,
        'dead_report_codes' => [],
        'dead_place' => null,
        'autopsy_perform' => null,
        'patient_status_note' => null,
        'discharge_cr' => null,
        'date_discharge_cr' => null,
        'one_week_cr' => null,
        'date_one_week_cr' => null,
        'one_month_cr' => null,
        'date_one_month_cr' => null,
        'three_month_cr' => null,
        'date_three_month_cr' => null,
        'six_month_cr' => null,
        'date_six_month_cr' => null,
        'remark' => null,
    ];

    public function __invoke(array $data, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'hn' => ['required', 'digits:8', new HnExists],
            'date_transplant' => ['required', 'date'],
            'case_no' => ['required', 'integer', 'min:1', 'max:200'],
            'donor_type' => ['required', 'in:CD single kidney,CD dual kidneys,LD'],
            'donor_hn' => ['nullable', 'required_if:donor_type,LD', new HnExists],
            'donor_name' => ['nullable', 'required_if:donor_type,LD', 'string', 'min:10'],
            'donor_redcross_id' => ['nullable', 'required_if:donor_type,CD single kidney,CD dual kidneys', 'string', 'max:10'],
            'donor_hospital' => ['nullable', 'required_if:donor_type,CD single kidney,CD dual kidneys', 'exists:hospitals,name'],
        ]);

        $dateTx = Carbon::create($validated['date_transplant']);
        $year = $dateTx->year;
        $thaiYear = $year + 543;
        $caseNo = (int) $validated['case_no'];
        $ktNo = ($thaiYear % 100).'-'.($caseNo > (99) ? $caseNo : str_pad($caseNo, 2, '0', STR_PAD_LEFT));
        if ($case = KidneyTransplantSurvivalCaseRecord::query()
            ->where('status', '!=', KidneyTransplantSurvivalCaseStatus::DELETED)
            ->where('meta->kt_no', $ktNo)
            ->first()
        ) {
            return ['key' => $case->hashed_key];
        }

        $recipientId = '1'.$year.str_pad($caseNo, 3, '0', STR_PAD_LEFT);
        $donorId = $this->genDonorId($year, (int) $validated['donor_redcross_id']);

        $patient = Patient::query()->findByHashKey($validated['hn'])->first();
        $admission = $this->findTxAdmission($patient->hn, $dateTx);

        $case = new KidneyTransplantSurvivalCaseRecord;
        $case->patient_id = $patient->id;
        $case->form = $this->FORM_TEMPLATE;
        if ($validated['donor_hn']) {
            $donor = Patient::query()->findByHashKey($validated['donor_hn'])->first();
            $case->form['donor_gender'] = $donor->gender;
            $case->form['donor_age'] = (int) abs($donor->dob->diffInYears($dateTx));
        }
        $case->form['date_last_update'] = now()->format('Y-m-d');
        $case->status = KidneyTransplantSurvivalCaseStatus::ACTIVE;
        $case->meta = [
            'version' => $this->CRF_VERSION,
            'date_transplant' => $validated['date_transplant'],
            'donor_type' => $validated['donor_type'],
            'kt_no' => $ktNo,
            'recipient_id' => (int) $recipientId,
            'donor_id' => $donorId,
            'donor_redcross_id' => $validated['donor_redcross_id'],
            'hn' => $patient->hn,
            'an' => $admission->an,
            'name' => $admission->patient->full_name,
            'month' => $dateTx->month,
            'no_patient_record' => false,
            'no_patient_dob' => false,
            'no_donor_hn' => false,
        ];
        $case->save();
        $case->update(['meta->title' => $case->genTitle()]);
        $this->finishing($case, $case->patient, $user, $this->REGISTRY_ID);

        $this->updateCreatinine($case);

        $form = $case->form;
        $form['date_update_graft_status'] = $form['date_latest_cr'];
        $form['date_update_patient_status'] = $form['date_latest_cr'];
        $form['date_last_update'] = $form['date_latest_cr'];
        $form['donor_type'] = $validated['donor_type'];
        $form['donor_hn'] = $validated['donor_hn'];
        $form['donor_name'] = $validated['donor_name'];
        $form['donor_redcross_id'] = $validated['donor_redcross_id'];
        $form['donor_hospital'] = $validated['donor_hospital'];
        $case->form = $form;
        $case->save();

        return ['key' => $case->hashed_key];
    }

    protected function findTxAdmission(string|int $hn, Carbon $dateTx): Admission
    {
        $api = new PortalAPI;
        $admissionData = $api->getPatientAdmissions($hn);
        if (! $admissionData['found']) {
            throw ValidationException::withMessages(['hn' => 'No admission found']);
        }

        $admissionTxFiltered = collect($admissionData['admissions'])
            ->filter(static fn ($admission) => $dateTx->greaterThanOrEqualTo(Carbon::create(explode(' ', $admission['admitted_at'])[0]))
                && $dateTx->lessThan(Carbon::create(explode(' ', $admission['discharged_at'])[0]))
            )->first();

        if (! $admissionTxFiltered) {
            throw ValidationException::withMessages(['hn' => 'No admission found']);
        }

        return (new AdmissionManager)->manage($admissionTxFiltered['an'])['admission'];
    }

    public function createWithPatient(Patient $patient, string $dateTx, int $recipientId, string $ktNo, User $user, bool $noPatient = false): KidneyTransplantSurvivalCaseRecord
    {
        $case = new KidneyTransplantSurvivalCaseRecord;

        $case->patient_id = $patient->id;
        $case->form = $this->FORM_TEMPLATE;
        $case->form['date_last_update'] = now()->format('Y-m-d');
        $case->status = KidneyTransplantSurvivalCaseStatus::ACTIVE;
        $case->meta = [
            'version' => $this->CRF_VERSION,
            'date_transplant' => $dateTx,
            'donor_type' => null,
            'kt_no' => $ktNo,
            'recipient_id' => $recipientId,
            'donor_id' => null,
            'donor_redcross_id' => null,
            'hn' => $patient->hn,
            'an' => null,
            'name' => $patient->full_name,
            'month' => Carbon::create($dateTx)->month,
            'no_patient_record' => $noPatient,
            'no_patient_dob' => $patient->dob->year === 1900,
            'no_donor_hn' => false,
        ];
        $case->save();
        $case->update(['meta->title' => $case->genTitle()]);
        $this->finishing($case, $case->patient, $user, $this->REGISTRY_ID);

        $this->updateCreatinine($case);

        return $case;
    }

    public function createWithAdmission(Admission $admission, Carbon $dateTx, int $recipientId, string $ktNo, User $user): KidneyTransplantSurvivalCaseRecord
    {
        $case = new KidneyTransplantSurvivalCaseRecord;

        $case->patient_id = $admission->patient->id;
        $case->form = $this->FORM_TEMPLATE;
        $case->form['date_last_update'] = now()->format('Y-m-d');
        $case->status = KidneyTransplantSurvivalCaseStatus::ACTIVE;
        $case->meta = [
            'version' => $this->CRF_VERSION,
            'date_transplant' => $dateTx->format('Y-m-d'),
            'donor_type' => null,
            'kt_no' => $ktNo,
            'recipient_id' => $recipientId,
            'donor_id' => null,
            'donor_redcross_id' => null,
            'hn' => $admission->patient->hn,
            'an' => $admission->an,
            'name' => $admission->patient->full_name,
            'month' => $dateTx->month,
            'no_patient_record' => false,
            'no_patient_dob' => false,
            'no_donor_hn' => false,
        ];
        $case->save();
        $case->update(['meta->title' => $case->genTitle()]);
        $this->finishing($case, $case->patient, $user, $this->REGISTRY_ID);

        $this->updateCreatinine($case);

        return $case;
    }

    protected function genDonorId(int $year, ?int $redcrossId): int
    {
        if ($redcrossId) {
            if ($coDonor = KidneyTransplantSurvivalCaseRecord::query()
                ->where('form->donor_redcross_id', "$redcrossId")
                ->first()) {
                return $coDonor->meta['donor_id'];
            }
        }

        $maxDonorId = 0;
        KidneyTransplantSurvivalCaseRecord::query()
            ->where('meta->date_transplant', 'like', $year.'-%')
            ->each(function ($case) use(&$maxDonorId) {
                $maxDonorId = max($case->meta['donor_id'], $maxDonorId);
            });

        if ($maxDonorId) {
            return $maxDonorId + 1;
        }

        return '9'.$year.'001';
    }
}
