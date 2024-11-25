<?php

namespace App\Actions\Clinics\PostKT;

use App\APIs\PortalAPI;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Admission;
use App\Models\User;
use App\Rules\AnExists;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class CaseStoreAction extends CaseBaseAction
{
    use CaseRecordFinishable;

    protected float $CRF_VERSION = 1.0;

    public function __invoke(array $data, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'an' => ['required', 'digits:8', new AnExists],
            'date_transplant' => ['required', 'date'],
            'case_no' => ['required', 'integer', 'min:1', 'max:200'],
        ]);

        $admission = Admission::query()->findByHashKey($validated['an'])->first();
        $year = $admission->encountered_at->tz('Asia/Bangkok')->year;
        $thaiYear = $year + 543;
        $caseNo = (int) $validated['case_no'];
        $ktNo = ($thaiYear % 100) . '-' . ($caseNo > (99) ? $caseNo : str_pad($caseNo, 2, '0', STR_PAD_LEFT));
        $ktId = '1' . $year . str_pad($caseNo, 3, '0', STR_PAD_LEFT);

        if ($case = KidneyTransplantSurvivalCaseRecord::query()
            ->where('form->kt_no', $ktNo)
            ->first()
        ) {
            return ['key' => $case->hashed_key];
        }

        $case = new KidneyTransplantSurvivalCaseRecord();

        $case->patient_id = $admission->patient_id;
        $case->form = [
            'date_transplant' => $validated['date_transplant'],
            'kt_no' => $ktNo,
            'kt_id' => (int) $ktId,
            'graft_status' => 'graft function',
            'date_update_graft_status' => null,
            'date_graft_loss' => null,
            'graft_loss_codes' => [],
            'graft_loss_note' => null,
            'date_latest_cr' => null,
            'latest_cr' => null,
            'patient_status' => 'alive',
            'date_update_patient_status' => null,
            'date_dead' => null,
            'dead_report_codes' => [],
            'dead_place' => null,
            'dead_note' => null,
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
        ];
        $case->status = KidneyTransplantSurvivalCaseStatus::ACTIVE;
        $case->meta = [
            'version' => $this->CRF_VERSION,
            'hn' => $admission->patient->hn,
            'an' => $admission->an,
            'name' => $admission->patient->full_name,
        ];
        $case->save();
        $case->update(['meta->title' => $case->genTitle()]);
        $this->finishing($case, $case->patient, $user, $this->REGISTRY_ID);

        $this->updateCreatinine($case);

        return ['key' => $case->hashed_key];
    }
}
