<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Rules\AnExists;
use App\Rules\HnExists;
use App\Traits\AvatarLinkable;
use App\Traits\CaseRecordFinishable;
use Illuminate\Support\Facades\Validator;

class CaseRecordStoreAction extends AcuteHemodialysisAction
{
    use AvatarLinkable, CaseRecordFinishable;

    protected float $CRF_VERSION = 1.0;

    protected array $FORM_TEMPLATE = [
        'previous_crrt' => false,
        'date_start_crrt' => null,
        'date_end_crrt' => null,
        'renal_diagnosis' => null,
        'admission_diagnosis' => null,
        'comorbidities' => [
            'dm' => false,
            'ht' => false,
            'dlp' => false,
            'coronary_artery_disease' => false,
            'cerebrovascular_disease' => false,
            'copd' => false,
            'cirrhosis' => false,
            'cancer' => false,
            'other' => null,
        ],
        'indications' => [
            'volume_overload' => false,
            'metabolic_acidosis' => false,
            'hyperkalemia' => false,
            'toxin_removal' => false,
            'initiate_chronic_hd' => false,
            'maintain_chronic_hd' => false,
            'change_from_pd' => false,
            'uremia' => false,
            'delayed_graft_function' => false,
            'other' => null,
        ],
        'hbs_ag' => null,
        'date_hbs_ag' => null,
        'anti_hcv' => null,
        'date_anti_hcv' => null,
        'anti_hiv' => null,
        'date_anti_hiv' => null,
        'opd_consent_form' => null,
        'ipd_consent_form' => null,
        'same_consent_form' => false,
        'insurance' => null,
        'renal_outcome' => null,
        'cr_before_discharge' => null,
        'patient_outcome' => null,
        'cause_of_dead' => null,
    ];

    public function __invoke(array $data, mixed $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::make($data, [
            'hn' => ['required', 'digits:8', new HnExists],
            'an' => ['digits:8', new AnExists],
        ])->validate();

        if ($caseRecord = CaseRecord::query()->where('status', 1)->where('meta->hn', $validated['hn'])->first()) {
            return ['key' => $caseRecord->hashed_key];
        }
        $caseRecord = new CaseRecord();
        $patient = Patient::query()->findByHashedKey($validated['hn'])->first();
        $caseRecord->patient_id = $patient->id;
        $form = $this->FORM_TEMPLATE;
        $an = null;
        if ($validated['an'] ?? false) {
            $admission = Admission::query()->findByHashedKey($validated['an'])->first();
            if (! $admission->dismissed_at) { // active admission
                $an = $admission->an;
            }
        }
        $caseRecord->form = $form;
        $caseRecord->meta = [
            'version' => $this->CRF_VERSION,
            'hn' => $patient->hn,
            'an' => $an,
            'name' => $patient->first_name,
            'ward_admit' => null,
        ];
        $caseRecord->save();
        $caseRecord->update(['meta->title' => $caseRecord->genTitle()]);
        $this->finishing($caseRecord, $patient, $user, $this->REGISTRY_ID);

        return ['key' => $caseRecord->hashed_key];
    }
}
