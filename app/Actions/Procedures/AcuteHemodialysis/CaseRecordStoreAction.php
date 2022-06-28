<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\CaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Models\User;
use App\Rules\AnExists;
use App\Rules\HnExists;
use Illuminate\Support\Facades\Validator;

class CaseRecordStoreAction extends AcuteHemodialysisAction
{
    protected $CRF_VERSION = 1.0;

    protected $FORM_TEMPLATE = [
        'an' => null,
        'ward_admit' => null,
        'ward_discharge' => null,
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

    /**
     * @todo authorize
     */
    public function __invoke(array $data, User $user): CaseRecord
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, [
            'hn' => ['required', 'digits:8', new HnExists],
            'an' => ['digits:8', new AnExists],
        ])->validate();

        $caseRecord = new CaseRecord();
        $patient = Patient::query()->findByHashedKey($validated['hn'])->first();
        $caseRecord->patient_id = $patient->id;
        $caseRecord->registry_id = $this->REGISTRY_ID;
        $form = $this->FORM_TEMPLATE;
        if ($validated['an'] ?? false) {
            $admission = Admission::query()->findByHashedKey($validated['an'])->first();
            if (! $admission->dismissed_at) { // active admission
                $form['an'] = $admission->an;
            }
        }
        $caseRecord->form = $form;
        $caseRecord->meta = [
            'hn' => $patient->hn,
            'name' => $patient->first_name,
            'version' => $this->CRF_VERSION,
        ];
        $caseRecord->creator_id = $user->id;
        $caseRecord->updater_id = $user->id;
        $caseRecord->save();

        return $caseRecord;
    }
}
