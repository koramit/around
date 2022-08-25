<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Patient;
use App\Models\Subscription;
use App\Models\User;
use App\Rules\AnExists;
use App\Rules\HnExists;
use Illuminate\Support\Facades\Validator;

class CaseRecordStoreAction extends AcuteHemodialysisAction
{
    protected float $CRF_VERSION = 1.0;

    protected array $FORM_TEMPLATE = [
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

    public function __invoke(array $data, User $user): CaseRecord
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, [
            'hn' => ['required', 'digits:8', new HnExists],
            'an' => ['digits:8', new AnExists],
        ])->validate();

        if ($caseRecord = CaseRecord::query()->where('status', 1)->where('meta->hn', $validated['hn'])->first()) {
            return $caseRecord;
        }
        $caseRecord = new CaseRecord();
        $patient = Patient::query()->findByHashedKey($validated['hn'])->first();
        $caseRecord->patient_id = $patient->id;
        $form = $this->FORM_TEMPLATE;
        if ($validated['an'] ?? false) {
            $admission = Admission::query()->findByHashedKey($validated['an'])->first();
            if (! $admission->dismissed_at) { // active admission
                $form['an'] = $admission->an;
            }
        }
        $caseRecord->form = $form;
        $now = now()->format('M j y');
        $caseRecord->meta = [
            'version' => $this->CRF_VERSION,
            'hn' => $patient->hn,
            'name' => $patient->first_name,
            'title' => "Acute Hemodialysis Case : HN $patient->hn $patient->first_name : $now",
            'ward_admit' => null,
        ];
        $caseRecord->save();

        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'create',
        ]);

        if ($patient->registries()->where('registry_id', $this->REGISTRY_ID)->count() === 0) {
            $patient->registries()->attach($this->REGISTRY_ID);
        }

        $sub = Subscription::query()->create([
            'subscribable_type' => $caseRecord::class,
            'subscribable_id' => $caseRecord->id,
        ]);

        if ($user->auto_subscribe_to_channel) {
            $user->subscriptions()->attach($sub->id);
        }

        return $caseRecord;
    }
}
