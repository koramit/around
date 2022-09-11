<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use App\Rules\SelectAtLeastOne;
use App\Traits\AcuteHemodialysis\CaseRecordShareValidatable;
use App\Traits\ChangesComparable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CaseRecordCompleteAction
{
    use CaseRecordShareValidatable, ChangesComparable;

    public function __invoke(array $data, string $hashed, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        /** @var AcuteHemodialysisCaseRecord $caseRecord */
        $caseRecord = AcuteHemodialysisCaseRecord::query()->findByUnhashKey($hashed)->firstOrFail();

        if (
            $user->cannot('complete', $caseRecord)
            && $user->cannot('addendum', $caseRecord)
        ) {
            abort(403);
        }

        $rules = [
            'renal_outcome' => ['required', Rule::in($this->RENAL_OUTCOMES)],
            'patient_outcome' => ['required', Rule::in($this->PATIENT_OUTCOMES)],
            'cause_of_dead' => ['required_if:patient_outcome,Dead', 'nullable', 'string', 'max:128'],
            'cr_before_discharge' => ['required_if:renal_outcome,Recovery', 'nullable', 'numeric'],

            'previous_crrt' => ['required', 'boolean'],
            'date_start_crrt' => ['required_if:previous_crrt,true', 'nullable', 'date'],
            'date_end_crrt' => ['required_unless:date_start_crrt,null', 'nullable', 'date'],

            'renal_diagnosis' => ['required', Rule::in($this->RENAL_DIAGNOSIS)],
            'admission_diagnosis' => ['required_unless:admission.an,null', 'nullable', 'string', 'max:255'],

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
            'indications' => (new SelectAtLeastOne),

            'hbs_ag' => ['required', Rule::in($this->SEROLOGY_RESULTS)],
            'date_hbs_ag' => 'nullable|date',
            'anti_hcv' => ['required', Rule::in($this->SEROLOGY_RESULTS)],
            'date_anti_hcv' => 'nullable|date',
            'anti_hiv' => ['required', Rule::in($this->SEROLOGY_RESULTS)],
            'date_anti_hiv' => 'nullable|date',
            'opd_consent_form' => ['required_if:ipd_consent_form,null', 'nullable', 'string', 'max:128'],
            'ipd_consent_form' => ['required_if:opd_consent_form,null', 'nullable', 'string', 'max:128'],
            'same_consent_form' => 'boolean',
            'insurance' => ['required', 'string', 'max:60'],
        ];

        if ($user->can('force_complete_case') && isset($data['force'])) {
            unset($rules['opd_consent_form'], $rules['ipd_consent_form']);
        }

        $validated = Validator::make($data, $rules)->validate();

        if ($caseRecord->status === 'completed') {
            return $this->addendum($caseRecord, $validated, $user->id);
        }

        $caseRecord->form = $validated;
        $caseRecord->status = 'completed';
        $caseRecord->actionLogs()->create([
            'action' => 'complete',
            'actor_id' => $user->id,
        ]);
        $caseRecord->save();

        return [
            'type' => 'success',
            'title' => 'Case complete successfully',
            'message' => "Acute HD case HN {$caseRecord->meta['hn']} {$caseRecord->meta['name']} completed.",
        ];
    }

    private function addendum(AcuteHemodialysisCaseRecord $caseRecord, array $validated, int $userId): array
    {
        $diff = $this->formJsonDiff($caseRecord->form, $validated);
        if (! count($diff)) {
            return [
                'type' => 'warning',
                'title' => 'No updates',
                'message' => "Acute HD case HN {$caseRecord->meta['hn']} {$caseRecord->meta['name']} no any updates provided.",
            ];
        }
        $caseRecord->update(['form' => $validated]);
        $caseRecord->actionLogs()->create([
            'action' => 'change',
            'actor_id' => $userId,
            'payload' => $diff,
        ]);

        return [
            'type' => 'success',
            'title' => 'Case update successfully',
            'message' => "Acute HD case HN {$caseRecord->meta['hn']} {$caseRecord->meta['name']} updated.",
        ];
    }
}
