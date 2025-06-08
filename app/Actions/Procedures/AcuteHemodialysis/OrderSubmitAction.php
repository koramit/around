<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyOrderResubmitToSubscribers;
use App\Jobs\Procedures\AcuteHemodialysis\ShouldNotifyOrderSubmittedWithoutConsentForm;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Rules\AcceptedIfOthersFalsy;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use App\Traits\AvatarLinkable;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderSubmitAction extends AcuteHemodialysisAction
{
    use AvatarLinkable, OrderFormConfigsShareable;

    public function __invoke(array $data, string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        /** @var AcuteHemodialysisOrderNote $note */
        $note = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('submit', $note)) {
            abort(403);
        }

        $rules = [];
        if (isset($data['hd'])) {
            if (strpos($note->meta['dialysis_type'], '+HF')) {
                $rules = array_merge($rules, [
                    'hd.hf_perform_at' => ['required', Rule::in($this->FORM_CONFIGS['hf_perform_at'])],
                    'hd.hf_ultrafiltration_min' => [
                        'required',
                        'integer',
                        'between:'.$this->FORM_CONFIGS['validators']['hf_ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['hf_ultrafiltration_min']['max'],
                    ],
                    'hd.hf_ultrafiltration_max' => [
                        'required',
                        'integer',
                        'between:'.$this->FORM_CONFIGS['validators']['hf_ultrafiltration_max']['min'].','.$this->FORM_CONFIGS['validators']['hf_ultrafiltration_max']['max'],
                    ],
                ]);
            }
            $rules = array_merge($rules, [
                'hd.access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
                'hd.access_site_coagulant' => [
                    'prohibited_if:hd.access_type,pending',
                    Rule::requiredIf(fn () => $data['hd']['access_type'] !== 'pending'),
                    'nullable',
                    Rule::in(str_starts_with($data['hd']['access_type'] ?? '', 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
                ],
                'hd.dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                'hd.dialysate' => ['required', Rule::in($this->FORM_CONFIGS['dialysates'])],
                'hd.dialysate_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
                'hd.reverse_dialysate_flow' => 'required|boolean',
                'hd.blood_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'hd.dialysate_temperature' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
                'hd.sodium' => [
                    'required',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['sodium']['min'].','.$this->FORM_CONFIGS['validators']['sodium']['max'],
                ],
                'hd.sodium_profile' => 'required|boolean',
                'hd.sodium_profile_start' => 'required_if:hd.sodium_profile,true|nullable|integer',
                'hd.sodium_profile_end' => 'required_unless:hd.sodium_profile_start,null|nullable|integer',
                'hd.bicarbonate' => ['required', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
                'hd.anticoagulant' => 'required|string|max:255',
                'hd.anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
                'hd.anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
                'hd.heparin_loading_dose' => [
                    'required_if:hd.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max'],
                ],
                'hd.heparin_maintenance_dose' => [
                    'required_if:hd.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max'],
                ],
                'hd.enoxaparin_dose' => [
                    'required_if:hd.anticoagulant,enoxaparin',
                    'nullable',
                    'numeric',
                    'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max'],
                ],
                'hd.fondaparinux_bolus_dose' => ['required_if:hd.anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
                'hd.tinzaparin_dose' => [
                    'required_if:hd.anticoagulant,tinzaparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max'],
                ],
                'hd.ultrafiltration_min' => [
                    'required_if:hd.dry_weight,null',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['max'],
                ],
                'hd.ultrafiltration_max' => [
                    'required_unless:hd.ultrafiltration_min,null',
                    'nullable',
                    'integer',
                    'between:'.($data['hd']['ultrafiltration_min'] ?? 0).','.$this->FORM_CONFIGS['validators']['ultrafiltration_max']['max'],
                ],
                'hd.dry_weight' => 'required_if:hd.ultrafiltration_min,null|nullable|numeric',
                'hd.glucose_50_percent_iv_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_volumes'])],
                'hd.glucose_50_percent_iv_at' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_at'])],
                'hd.albumin_20_percent_prime' => ['nullable', Rule::in($this->FORM_CONFIGS['albumin_20_percent_primes'])],
                'hd.nutrition_iv_type' => 'nullable|string|max:255',
                'hd.nutrition_iv_volume' => 'nullable|integer',
                'hd.prc_volume' => 'nullable|numeric',
                'hd.ffp_volume' => 'nullable|integer',
                'hd.platelet_volume' => 'nullable|numeric',
                'hd.transfusion_other' => 'nullable|string|max:255',
                'hd.catheter_lock' => ['nullable', 'required_if:hd.access_type,DLC,Perm cath', 'string', 'max:255'],
            ]);
        }

        if (isset($data['hf'])) {
            $rules = array_merge($rules, [
                'hf.access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
                'hf.access_site_coagulant' => [
                    'prohibited_if:hf.access_type,pending',
                    Rule::requiredIf(fn () => $data['hf']['access_type'] !== 'pending'),
                    'nullable',
                    Rule::in(str_starts_with($data['hf']['access_type'] ?? '', 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
                ],
                'hf.dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                'hf.blood_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'hf.anticoagulant' => 'required|string|max:255',
                'hf.anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
                'hf.anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
                'hf.heparin_loading_dose' => [
                    'required_if:hf.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max'],
                ],
                'hf.heparin_maintenance_dose' => [
                    'required_if:hf.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max'],
                ],
                'hf.enoxaparin_dose' => [
                    'required_if:hf.anticoagulant,enoxaparin',
                    'nullable',
                    'numeric',
                    'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max'],
                ],
                'hf.fondaparinux_bolus_dose' => ['required_if:hf.anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
                'hf.tinzaparin_dose' => [
                    'required_if:hf.anticoagulant,tinzaparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max'],
                ],
                'hf.ultrafiltration_min' => [
                    'required_if:hf.dry_weight,null',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['max'],
                ],
                'hf.ultrafiltration_max' => [
                    'required_unless:hf.ultrafiltration_min,null',
                    'nullable',
                    'integer',
                    'between:'.$data['hf']['ultrafiltration_min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_max']['max'],
                ],
                'hf.dry_weight' => 'required_if:hf.ultrafiltration_min,null|nullable|numeric',
                'hf.glucose_50_percent_iv_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_volumes'])],
                'hf.albumin_20_percent_prime' => ['nullable', Rule::in($this->FORM_CONFIGS['albumin_20_percent_primes'])],
                'hf.nutrition_iv_type' => 'nullable|string|max:255',
                'hf.nutrition_iv_volume' => 'nullable|integer',
                'hf.prc_volume' => 'nullable|numeric',
                'hf.ffp_volume' => 'nullable|integer',
                'hf.platelet_volume' => 'nullable|numeric',
                'hf.transfusion_other' => 'nullable|string|max:255',
                'hf.catheter_lock' => ['nullable', 'required_if:hf.access_type,DLC,Perm cath', 'string', 'max:255'],
            ]);
        }

        if (isset($data['pe'])) {
            $rules = array_merge($rules, [
                'pe.technique' => ['required', Rule::in($this->FORM_CONFIGS['pe_technique_options'])],
                'pe.access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
                'pe.access_site_coagulant' => [
                    'prohibited_if:pe.access_type,pending',
                    Rule::requiredIf(fn () => $data['pe']['access_type'] !== 'pending'),
                    'nullable',
                    Rule::in(str_starts_with($data['pe']['access_type'] ?? '', 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
                ],
                'pe.dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['pe_dialyzers'])],
                'pe.dialyzer_second' => ['nullable', 'required_if:pe.technique,DFPP', Rule::in($this->FORM_CONFIGS['pe_dialyzer_second_options'])],
                'pe.replacement_fluid_albumin' => 'boolean',
                'pe.replacement_fluid_albumin_concentrated' => ['required_if:pe.replacement_fluid_albumin,true', 'nullable', Rule::in($this->FORM_CONFIGS['replacement_fluid_albumin_concentrated'])],
                'pe.replacement_fluid_albumin_volume' => 'required_if:pe.replacement_fluid_albumin,true|nullable|integer',
                'pe.replacement_fluid_ffp' => 'boolean',
                'pe.replacement_fluid_ffp_volume' => 'required_if:replacement_fluid_ffp_volume,true|nullable|integer',
                'pe.blood_pump' => ['required', Rule::in($this->FORM_CONFIGS['blood_pump'])],
                'pe.filtration_pump' => ['required', Rule::in($this->FORM_CONFIGS['pe_filtration_pump_options'])],
                'pe.replacement_pump' => ['required', Rule::in($this->FORM_CONFIGS['pe_replacement_pump_options'])],
                'pe.percent_discard' => ['nullable', 'required_if:pe.technique,DFPP', Rule::in($this->FORM_CONFIGS['pe_percent_discard_options'])],
                'pe.drain_pump' => 'nullable|integer',
                'pe.calcium_gluconate_10_percent_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_volumes'])],
                'pe.calcium_gluconate_10_percent_timing' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_timings'])],
                'pe.anticoagulant' => 'required|string|max:255',
                'pe.anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
                'pe.anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
                'pe.heparin_loading_dose' => [
                    'required_if:pe.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max'],
                ],
                'pe.heparin_maintenance_dose' => [
                    'required_if:pe.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max'],
                ],
                'pe.enoxaparin_dose' => [
                    'required_if:pe.anticoagulant,enoxaparin',
                    'nullable',
                    'numeric',
                    'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max'],
                ],
                'pe.fondaparinux_bolus_dose' => ['required_if:pe.anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
                'pe.tinzaparin_dose' => [
                    'required_if:pe.anticoagulant,tinzaparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max'],
                ],
                'pe.catheter_lock' => ['nullable', 'required_if:pe.access_type,DLC,Perm cath', 'string', 'max:255'],
            ]);
        }

        if (isset($data['sledd'])) {
            $rules = array_merge($rules, [
                'sledd.duration' => ['required', Rule::in($this->FORM_CONFIGS['sledd_durations'])],
                'sledd.access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
                'sledd.access_site_coagulant' => [
                    'prohibited_if:sledd.access_type,pending',
                    Rule::requiredIf(fn () => $data['sledd']['access_type'] !== 'pending'),
                    'nullable',
                    Rule::in(str_starts_with($data['sledd']['access_type'] ?? '', 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
                ],
                'sledd.dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                'sledd.dialysate' => ['required', Rule::in($this->FORM_CONFIGS['dialysates'])],
                'sledd.dialysate_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
                'sledd.reverse_dialysate_flow' => 'required|boolean',
                'sledd.blood_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'sledd.dialysate_temperature' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
                'sledd.sodium' => [
                    'required',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['sodium']['min'].','.$this->FORM_CONFIGS['validators']['sodium']['max'],
                ],
                'sledd.sodium_profile' => 'required|boolean',
                'sledd.sodium_profile_start' => 'required_if:sledd.sodium_profile,true|nullable|integer',
                'sledd.sodium_profile_end' => 'required_unless:sledd.sodium_profile_start,null|nullable|integer',
                'sledd.bicarbonate' => ['required', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
                'sledd.anticoagulant' => 'required|string|max:255',
                'sledd.anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
                'sledd.anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
                'sledd.heparin_loading_dose' => [
                    'required_if:sledd.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max'],
                ],
                'sledd.heparin_maintenance_dose' => [
                    'required_if:sledd.anticoagulant,heparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max'],
                ],
                'sledd.enoxaparin_dose' => [
                    'required_if:sledd.anticoagulant,enoxaparin',
                    'nullable',
                    'numeric',
                    'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max'],
                ],
                'sledd.fondaparinux_bolus_dose' => ['required_if:sledd.anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
                'sledd.tinzaparin_dose' => [
                    'required_if:sledd.anticoagulant,tinzaparin',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max'],
                ],
                'sledd.ultrafiltration_min' => [
                    'required_if:sledd.dry_weight,null',
                    'nullable',
                    'integer',
                    'between:'.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['max'],
                ],
                'sledd.ultrafiltration_max' => [
                    'required_unless:sledd.ultrafiltration_min,null',
                    'nullable',
                    'integer',
                    'between:'.$data['sledd']['ultrafiltration_min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_max']['max'],
                ],
                'sledd.dry_weight' => 'required_if:sledd.ultrafiltration_min,null|nullable|numeric',
                'sledd.glucose_50_percent_iv_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_volumes'])],
                'sledd.glucose_50_percent_iv_at' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_at'])],
                'sledd.albumin_20_percent_prime' => ['nullable', Rule::in($this->FORM_CONFIGS['albumin_20_percent_primes'])],
                'sledd.nutrition_iv_type' => 'nullable|string|max:255',
                'sledd.nutrition_iv_volume' => 'nullable|integer',
                'sledd.prc_volume' => 'nullable|numeric',
                'sledd.ffp_volume' => 'nullable|integer',
                'sledd.platelet_volume' => 'nullable|numeric',
                'sledd.transfusion_other' => 'nullable|string|max:255',
                'sledd.catheter_lock' => ['nullable', 'required_if:sledd.access_type,DLC,Perm cath', 'string', 'max:255'],
                'sledd.remark' => 'nullable|string|max:255',
            ]);
        }

        // base
        $rules = array_merge($rules, [
            'hemodynamic.stable' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['hemodynamic'])],
            'hemodynamic.hypotension' => 'declined_if:hemodynamic.stable,true',
            'hemodynamic.inotropic_dependent' => 'declined_if:hemodynamic.stable,true',
            'hemodynamic.severe_hypertension' => 'declined_if:hemodynamic.stable,true',
            'hemodynamic.bradycardia' => 'declined_if:hemodynamic.stable,true',
            'hemodynamic.arrhythmia' => 'declined_if:hemodynamic.stable,true',
            'respiration.stable' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['respiration'])],
            'respiration.hypoxia' => 'declined_if:respiration.stable,true',
            'respiration.high_risk_airway_obstruction' => 'declined_if:respiration.stable,true',

            'oxygen_support' => ['required', Rule::in($this->FORM_CONFIGS['oxygen_options'])],

            'neurological.stable' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['neurological'])],
            'neurological.gcs_drop' => 'declined_if:neurological.stable,true',
            'neurological.drowsiness' => 'declined_if:neurological.stable,true',

            'life_threatening_condition.stable' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['life_threatening_condition'])],
            'life_threatening_condition.acute_coronary_syndrome' => 'declined_if:life_threatening_condition.stable,true',
            'life_threatening_condition.cardiac_arrhythmia_with_hypotension' => 'declined_if:life_threatening_condition.stable,true',
            'life_threatening_condition.acute_ischemic_stroke' => 'declined_if:life_threatening_condition.stable,true',
            'life_threatening_condition.acute_ich' => 'declined_if:life_threatening_condition.stable,true',
            'life_threatening_condition.seizure' => 'declined_if:life_threatening_condition.stable,true',
            'life_threatening_condition.cardiac_arrest' => 'declined_if:life_threatening_condition.stable,true',

            'monitor.standard' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['monitor'])],
            'monitor.ekg' => 'declined_if:monitor.standard,true',
            'monitor.observe_chest_pain' => 'declined_if:monitor.standard,true',
            'monitor.observe_neuro_sign' => 'declined_if:monitor.standard,true',
            'monitor.other' => 'prohibited_if:monitor.standard,true|nullable|string|max:255',

            'predialysis_labs_request' => 'required|boolean',
            'postdialysis_bw' => 'required|boolean',
            'postdialysis_esa' => 'required|boolean',
            'postdialysis_iron_iv' => 'required|boolean',
            'treatments_request' => 'nullable|string|max:255',
        ]);

        $validated = Validator::make($data, $rules)->validate();

        $action = ($note->status === 'submitted' || ($note->meta['submitted'] ?? false)) ? 'resubmit' : 'submit';

        $note->actionLogs()->create([
            'action' => $action,
            'actor_id' => $user->id,
        ]);

        $note->form = $validated;
        $note->meta['submitted'] = true;
        if ($note->status !== 'scheduling') {
            $note->status = 'submitted';
        }
        $note->save();
        $this->shouldNotifyResubmit($note);
        ShouldNotifyOrderSubmittedWithoutConsentForm::dispatchAfterResponse($note);
        /*$this->notifyResubmit($note, $action);*/

        return [
            'case' => $note->caseRecord->hashed_key,
            'message' => [
                'type' => 'success',
                'title' => 'Submit successful.',
                'message' => '',
            ],
        ];
    }

    private function shouldNotifyResubmit(AcuteHemodialysisOrderNote $note): void
    {
        // the day before @ 20:00 local time
        $ref = $note->date_note->addDays(-1)->format('Y-m-d').' '.$this->LAST_HOUR_UTC;
        $ref = now()->create($ref);
        if (now()->lessThan($ref)) {
            return;
        }

        NotifyOrderResubmitToSubscribers::dispatchAfterResponse($note);
    }

    protected function notifyResubmit(AcuteHemodialysisOrderNote $note, string $action): void
    {
        if ($action !== 'resubmit') {
            return;
        }

        $note->load('author');
        $message = "\nOrder ถูกแก้ไข\n";
        $message .= "คนไข้ {$note->meta['name']} {$note->meta['dialysis_type']} \nวันที่ {$note->date_note->format('M j y')}\n";
        $message .= "โดย พ.{$note->author->first_name}";

        $sticker = collect([ // notify set
            ['packageId' => 789, 'stickerId' => 10873],
            ['packageId' => 789, 'stickerId' => 10892],
            ['packageId' => 1070, 'stickerId' => 17852],
            ['packageId' => 6359, 'stickerId' => 11069853],
            ['packageId' => 6359, 'stickerId' => 11069859],
            ['packageId' => 6359, 'stickerId' => 11069861],
            ['packageId' => 8522, 'stickerId' => 16581272],
            ['packageId' => 8522, 'stickerId' => 16581281],
            ['packageId' => 11537, 'stickerId' => 52002739],
            ['packageId' => 11537, 'stickerId' => 52002770],
            ['packageId' => 11538, 'stickerId' => 51626530],
        ])->random();

        try {
            Http::withToken(config('line_notify_group_chat.acute_hd'))
                ->asForm()
                ->post('https://notify-api.line.me/api/notify', [
                    'message' => $message,
                    'stickerPackageId' => $sticker['packageId'],
                    'stickerId' => $sticker['stickerId'],
                ]);

            // COUNT LINE NOTIFY
            $cacheKey = now()->format('Ym').'-LINE-NOTIFY-COUNT';
            cache()->increment($cacheKey);
        } catch (Exception $e) {
            Log::error("Failed to notify resubmit order\n".$e->getMessage());
        }
    }
}
