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
        $admission = Admission::query()->findByHashedKey($validated['an'])->first();
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
