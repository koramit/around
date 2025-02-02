<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Managers\Resources\PatientManager;
use App\Models\User;
use App\Rules\HnExists;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CaseUpdateAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, array $data, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);

        if ($user->cannot('update', $case)) {
            abort(403);
        }

        $newDonorHn = false;
        $donorHnRules = ['nullable', 'digits:8'];
        if ($data['donor_hn']
            && $data['donor_hn'] !== $case->form['donor_hn']
            && !$case->meta['no_donor_hn']
        ) {
            $newDonorHn = true;
            $donorHnRules = [new HnExists()];
        }

        $validated = Validator::validate($data, [
            'donor_hn' => $donorHnRules,
            'donor_gender' => ['nullable', 'in:male,female'],
            'donor_age' => ['nullable', 'integer', 'max:80'],
            'donor_hospital' => ['nullable', 'exists:hospitals,name'],
            'donor_cause_of_dead' => ['nullable', 'string', 'max:255'],
            'co_recipient_hospital' => ['nullable', 'exists:hospitals,name'],
            'donor_trauma' => ['bool'],
            'donor_is' => ['nullable', 'in:ฝาแฝด,น้อง,ลูกผู้น้อง,พี่,ลูกผู้พี่,บุตร,ภรรยา,สามี,มารดา,บิดา,หลาน,ป้า,ลุง,น้า,อา'],
            'donor_cause_of_death' => ['nullable', 'string', 'max:255'],
            'abo_incompatible' => ['bool'],
            'preemptive' => ['bool'],
            'combined_with_liver' => ['bool'],
            'combined_with_heart' => ['bool'],
            'combined_with_pancreas' => ['bool'],
            'nephrologist' => ['nullable', 'exists:people,name'],
            'surgeon' => ['nullable', 'exists:people,name'],
            'kt_times' => ['nullable', 'integer'],
            'medical_scheme' => ['nullable', 'string', 'max:255'],
            'pre_kt_prc_unit' => ['nullable', 'integer'],
            'cold_ischemic_time_hours' => ['nullable', 'integer'],
            'cold_ischemic_time_minutes' => ['nullable', 'integer'],
            'time_clamp_at_donor' => ['nullable', 'date'],
            'warm_ischemic_time_minutes' => ['nullable', 'integer'],
            'anastomosis_time_minutes' => ['nullable', 'integer'],
            'gestation_g' => ['nullable', 'integer'],
            'gestation_p' => ['nullable', 'integer'],
            'gestation_a' => ['nullable', 'integer'],
            'cause_of_esrd' => ['nullable', 'string'],
            'native_biopsy_report' => ['nullable', 'in:Yes,No'],
            'date_first_rrt' => ['nullable', 'date'],
            'rrt_mode' => ['nullable', 'in:PD,HD'],
            'baseline_cr' => ['nullable', 'numeric'],
            'pre_kt_cr' => ['nullable', 'numeric'],
            'crossmatch_cdc' => ['nullable', 'in:negative,positive'],
            'crossmatch_cdc_positive_specification' => ['nullable', 'string', 'max:255'],
            'crossmatch_cdc_ahg' => ['nullable', 'in:negative,positive'],
            'crossmatch_cdc_ahg_positive_specification' => ['nullable', 'string', 'max:255'],
            'crossmatch_flow_cxm' => ['nullable', 'in:negative,positive'],
            'crossmatch_flow_cxm_positive_specification' => ['nullable', 'string', 'max:255'],
            'last_pra_class_i_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'date_last_pra_class_i' => ['nullable', 'date'],
            'last_pra_class_ii_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'date_last_pra_class_ii' => ['nullable', 'date'],
            'peak_pra_class_i_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'date_peak_pra_class_i' => ['nullable', 'date'],
            'peak_pra_class_ii_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'date_peak_pra_class_ii' => ['nullable', 'date'],
            'mismatch_a' => ['nullable', 'in:0,1,2'],
            'mismatch_b' => ['nullable', 'in:0,1,2'],
            'mismatch_dr' => ['nullable', 'in:0,1,2'],
            'mismatch_cw' => ['nullable', 'in:0,1,2'],
            'mismatch_drb' => ['nullable', 'in:0,1,2'],
            'mismatch_dqb1' => ['nullable', 'in:0,1,2'],
            'mismatch_dpb1' => ['nullable', 'in:0,1,2'],
            'mismatch_mica' => ['nullable', 'in:0,1,2'],
            'mismatch_dqa1' => ['nullable', 'in:0,1,2'],
            'mismatch_dpa1' => ['nullable', 'in:0,1,2'],
            'recipient_cmv_igg' => ['nullable', 'in:negative,positive'],
            'donor_cmv_igg' => ['nullable', 'in:negative,positive'],
            'graft_function' => ['nullable', 'in:immediate graft function,slow graft function,delayed graft function,primary non-function'],
            'refer' => ['nullable', 'string', 'max:255'],
            'graft_status' => ['required', 'in:graft function,graft loss,loss follow up'],
            'date_update_graft_status' => ['required', 'date', 'before:tomorrow', 'after:date_transplant'],
            'date_graft_loss' => ['nullable', 'date', 'after:date_transplant'],
            'graft_loss_codes' => ['array'],
            'graft_loss_codes.*.code' => ['required', 'integer'],
            'graft_loss_codes.*.specification' => ['nullable', 'string', 'max:255'],
            'graft_loss_status_note' => ['nullable', 'string', 'max:512'],
            'date_latest_cr' => ['required', 'date', 'after_or_equal:date_transplant'],
            'latest_cr' => ['required', 'numeric'],
            'patient_status' => ['required', 'in:alive,dead,loss follow up'],
            'date_update_patient_status' => ['required', 'date', 'before:tomorrow', 'after:date_transplant'],
            'date_dead' => ['nullable', 'date', 'after:date_transplant'],
            'dead_report_codes' => ['array'],
            'dead_report_codes.*.code' => ['required', 'integer'],
            'dead_report_codes.*.specification' => ['nullable', 'string', 'max:255'],
            'patient_status_note' => ['nullable', 'string', 'max:512'],
            'discharge_cr' => ['nullable', 'numeric'],
            'date_discharge_cr' => ['nullable', 'date', 'after:date_transplant'],
            'one_week_cr' => ['nullable', 'numeric'],
            'date_one_week_cr' => ['nullable', 'date', 'after:date_transplant'],
            'one_month_cr' => ['nullable', 'numeric'],
            'date_one_month_cr' => ['nullable', 'date', 'after:date_transplant'],
            'three_month_cr' => ['nullable', 'numeric'],
            'date_three_month_cr' => ['nullable', 'date', 'after:date_transplant'],
            'six_month_cr' => ['nullable', 'numeric'],
            'date_six_month_cr' => ['nullable', 'date', 'after:date_transplant'],
            'managements' => ['array'],
            'managements.*.date_diagnosis' => ['nullable', 'date'],
            'managements.*.management' => ['nullable', 'string', 'max:1024'],
            'remark' => ['nullable', 'string', 'max:512'],
        ]);

        $dateTx = Carbon::create($case->meta['date_transplant']);
        $yearTh = abs($dateTx->diffInYears(Carbon::now()));
        for ($year = 1; $year <= $yearTh; $year++) {
            if (array_key_exists("year_{$year}_cr", $data)) {
                $validated["year_{$year}_cr"] = $data["year_{$year}_cr"];
                $validated["date_year_{$year}_cr"] = $data["date_year_{$year}_cr"];
            }
        }

        $validated['date_last_update'] = $case->form['date_last_update'];
        if ($newDonorHn) {
            $patient = (new PatientManager())->manage($validated['donor_hn'])['patient'];
            $validated['donor_name'] = $patient->full_name;
            $validated['donor_gender'] = $patient->gender;
            $validated['donor_age'] = (int) abs($patient->dob->diffInYears($dateTx));
        } else {
            $validated['donor_name'] = $case->form['donor_name'];
        }
        $case->form = $validated;
        $case->status = KidneyTransplantSurvivalCaseStatus::fromGraftPatientStatus($validated['graft_status'], $validated['patient_status']);
        $case->save();

        return [
            'type' => 'success',
            'title' => 'Case updated successfully.',
            'message' => $case->title,
        ];
    }
}
