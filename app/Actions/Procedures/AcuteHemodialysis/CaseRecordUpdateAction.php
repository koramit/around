<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\User;
use App\Traits\AcuteHemodialysis\CaseRecordShareValidatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CaseRecordUpdateAction extends AcuteHemodialysisAction
{
    use CaseRecordShareValidatable;

    public function __invoke(array $data, string $hashedKey, User $user): bool
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return true; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('update', $caseRecord)) {
            abort(403);
        }

        $validated = Validator::make($data, [
            'previous_crrt' => 'boolean',
            'date_start_crrt' => 'nullable|date',
            'date_end_crrt' => 'nullable|date',
            'renal_diagnosis' => ['nullable', Rule::in($this->RENAL_DIAGNOSIS)],
            'admission_diagnosis' => 'nullable|string|max:255',
            'comorbidities.dm' => 'boolean',
            'comorbidities.ht' => 'boolean',
            'comorbidities.dlp' => 'boolean',
            'comorbidities.coronary_artery_disease' => 'boolean',
            'comorbidities.cerebrovascular_disease' => 'boolean',
            'comorbidities.copd' => 'boolean',
            'comorbidities.cirrhosis' => 'boolean',
            'comorbidities.cancer' => 'boolean',
            'comorbidities.other' => 'nullable|string|max:512',
            'indications.volume_overload' => 'boolean',
            'indications.metabolic_acidosis' => 'boolean',
            'indications.hyperkalemia' => 'boolean',
            'indications.toxin_removal' => 'boolean',
            'indications.initiate_chronic_hd' => 'boolean',
            'indications.maintain_chronic_hd' => 'boolean',
            'indications.change_from_pd' => 'boolean',
            'indications.uremia' => 'boolean',
            'indications.delayed_graft_function' => 'boolean',
            'indications.other' => 'nullable|string|max:512',
            'hbs_ag' => ['nullable', Rule::in($this->SEROLOGY_RESULTS)],
            'date_hbs_ag' => 'nullable|date',
            'anti_hcv' => ['nullable', Rule::in($this->SEROLOGY_RESULTS)],
            'date_anti_hcv' => 'nullable|date',
            'anti_hiv' => ['nullable', Rule::in($this->SEROLOGY_RESULTS)],
            'date_anti_hiv' => 'nullable|date',
            'opd_consent_form' => 'nullable|string|max:128',
            'ipd_consent_form' => 'nullable|string|max:128',
            'same_consent_form' => 'boolean',
            'insurance' => 'nullable|string|max:60',
            'renal_outcome' => ['nullable', Rule::in($this->RENAL_OUTCOMES)],
            'cr_before_discharge' => 'nullable|numeric',
            'patient_outcome' => ['nullable', Rule::in($this->PATIENT_OUTCOMES)],
            'cause_of_dead' => 'nullable|string|max:128',
        ])->validate();

        $caseRecord->form = $validated;

        return $caseRecord->save();
    }
}
