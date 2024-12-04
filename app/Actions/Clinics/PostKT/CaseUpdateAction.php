<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\User;
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

        $validated = Validator::validate($data, [
            'graft_status' => ['required', 'in:graft function, graft loss, loss follow up'],
            'date_update_graft_status' => ['required', 'date', 'before:tomorrow', 'after:date_transplant'],
            'date_graft_loss' => ['nullable', 'date', 'after:date_transplant'],
            'graft_loss_codes' => ['array'],
            'graft_loss_codes.*.code' => ['required', 'integer'],
            'graft_loss_codes.*.specification' => ['nullable', 'string', 'max:255'],
            'graft_loss_note' => ['nullable', 'string', 'max:512'],
            'date_latest_cr' => ['required', 'date', 'after_or_equal:date_transplant'],
            'latest_cr' => ['required', 'numeric'],
            'patient_status' => ['required', 'in:alive,dead,loss follow up'],
            'date_update_patient_status' => ['required', 'date', 'before:tomorrow', 'after:date_transplant'],
            'date_dead' => ['nullable', 'date', 'after:date_transplant'],
            'dead_report_codes' => ['array'],
            'dead_report_codes.*.code' => ['required', 'integer'],
            'dead_report_codes.*.specification' => ['nullable', 'string', 'max:255'],
            'dead_note' => ['nullable', 'string', 'max:512'],
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
            'remarks' => ['nullable', 'string', 'max:512'],
        ]);

        $dateTx = Carbon::create($case->meta['date_transplant']);
        $yearTh = Carbon::now()->year - $dateTx->year;
        for ($year = 1; $year <= $yearTh; $year++) {
            if (array_key_exists("year_{$year}_cr", $data)) {
                $validated["year_{$year}_cr"] = $data["year_{$year}_cr"];
                $validated["date_year_{$year}_cr"] = $data["date_year_{$year}_cr"];
            }
        }

        $validated['date_last_update'] = $case->form['date_last_update'];
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
