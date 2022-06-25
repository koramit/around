<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;
use App\Rules\AcceptedIfOthersFalsy;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderSubmitAction extends AcuteHemodialysisAction
{
    use OrderFormConfigsShareable;

    public function __invoke(array $data, string $hashedKey, int $userId)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = Note::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $rules = [];
        if (isset($data['hd'])) {
            $rules = array_merge($rules, [
                // 'access_type' => *REQUIRED*|string|in[...],
                'hd.access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
                // 'access_site_coagulant' => *REQUIRED*|string|in[...], // if access_type == 'pending', must be null
                'hd.access_site_coagulant' => [
                    'prohibited_if:access_type,pending',
                    Rule::requiredIf(fn () => $data['hd']['access_type'] !== 'pending'),
                    Rule::in(str_starts_with($data['hd']['access_type'], 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
                ],
                // 'dialyzer' => *REQUIRED*|string|in[...],
                'hd.dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                // 'dialysate' => *REQUIRED*|string|in[...],
                'hd.dialysate' => ['required', Rule::in($this->FORM_CONFIGS['dialysates'])],
                // 'dialysate_flow_rate' => *REQUIRED*|integer|in[...],
                'hd.dialysate_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
                // 'reverse_dialysate_flow' => *REQUIRED*|bool,
                'hd.reverse_dialysate_flow' => 'required|boolean',
                // 'blood_flow_rate' => *REQUIRED*|integer|in[...],
                'hd.blood_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                // 'dialysate_temperature' => *REQUIRED*|integer|in[...],
                'hd.dialysate_temperature' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
                // 'sodium' => *REQUIRED*|integer|[128-145], // default 138
                'hd.sodium' => ['required', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['sodium']['min'].','.$this->FORM_CONFIGS['validators']['sodium']['max']],
                // 'sodium_profile' => *REQUIRED*|bool,
                'hd.sodium_profile' => 'required|boolean',
                // 'sodium_profile_start' => if sodium_profile|integer,
                'hd.sodium_profile_start' => 'required_if:sodium_profile,true|integer',
                // 'sodium_profile_end' => if sodium_profile|integer,
                'hd.sodium_profile_end' =>  [Rule::requiredIf(fn () => $data['hd']['sodium_profile_start']), 'integer'],
                // 'bicarbonate' => *REQUIRED*|integer|in[...],
                'hd.bicarbonate' => ['required', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
                // 'anticoagulant' => *REQUIRED*|string,
                'hd.anticoagulant' => 'required|string|max:255',
                // 'anticoagulant_none_drip_via_peripheral_iv' => *REQUIRED*|bool,
                'hd.anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
                // 'anticoagulant_none_nss_200ml_flush_q_hour' => *REQUIRED*|bool,
                'hd.anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
                // 'heparin_loading_dose' => if anticoagulant == heparin|integer|[250,2000],
                'hd.heparin_loading_dose' => ['required_if:anticoagulant,heparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max']],
                // 'heparin_maintenance_dose' => if anticoagulant == heparin|integer|[0,1500],
                'hd.heparin_maintenance_dose' => ['required_if:anticoagulant,heparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max']],
                // 'enoxaparin_dose' => if anticoagulant == enoxaparin|number|[0.3,0.8],
                'hd.enoxaparin_dose' => ['required_if:anticoagulant,enoxaparin', 'nullable', 'numeric', 'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max']],
                // 'fondaparinux_bolus_dose' => if anticoagulant == fondaparinux|integer|in[...],
                'hd.fondaparinux_bolus_dose' => ['required_if:anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
                // 'tinzaparin_dose' => if anticoagulant == tinzaparin|integer|[0,1500],
                'hd.tinzaparin_dose' => ['required_if:anticoagulant,tinzaparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max']],
                // 'ultrafiltration_min' => if dry_weight == null|integer|[0,5500],
                'hd.ultrafiltration_min' => ['required_if:dry_weight,null', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['max']],
                // 'ultrafiltration_max' => nullable|integer|[ultrafiltration_min,5500],
                'hd.ultrafiltration_max' => [Rule::requiredIf(fn () => $data['hd']['ultrafiltration_min']), 'nullable', 'integer', 'between:'.$data['hd']['ultrafiltration_min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_max']['max']],
                // 'dry_weight' => if ultrafiltration_min == null|number,
                'hd.dry_weight' => 'required_if:ultrafiltration_min,null|numeric',
                // 'glucose_50_percent_iv_volume' => nullable|integer|in[...],
                'hd.glucose_50_percent_iv_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_volumes'])],
                // 'glucose_50_percent_iv_at' => nullable|integer|in[...],
                'hd.glucose_50_percent_iv_at' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_at'])],
                // 'albumin_20_percent_prime' => nullable|integer|in[...],
                'hd.albumin_20_percent_prime' => ['nullable', Rule::in($this->FORM_CONFIGS['albumin_20_percent_primes'])],
                // 'nutrition_iv_type' => nullable|string|max:255,
                'hd.nutrition_iv_type' => 'nullable|string|max:255',
                // 'nutrition_iv_volume' => nullable|integer,
                'hd.nutrition_iv_volume' => 'nullable|integer',
                // 'post_dialysis_bw' => *REQUIRED*|bool,
                'hd.post_dialysis_bw' => 'required|boolean',
                // 'prc_volume' => nullable|integer,
                'hd.prc_volume' => 'nullable|integer',
                // 'ffp_volume' => nullable|integer,
                'hd.ffp_volume' => 'nullable|integer',
                // 'platelet_volume' => nullable|integer,
                'hd.platelet_volume' => 'nullable|integer',
                // 'transfusion_other' => nullable|string|max:255,
                'hd.transfusion_other' => 'nullable|string|max:255',
            ]);
        }

        // base
        $rules = array_merge($rules, [
            'hemodynamic.stable' => ['required', 'boolean', new AcceptedIfOthersFalsy($data['hemodynamic'])],
            'hemodynamic.hypotention' => 'declined_if:hemodynamic.stable,true',
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
            'life_threatening_condition.cardiac_arrhymia_with_hypotension' => 'declined_if:life_threatening_condition.stable,true',
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
            'postdialysis_esa' => 'required|boolean',
            'postdialysis_iron_iv' => 'required|boolean',
            'treatments_request' => 'nullable|string|max:255',
        ]);

        $validated = Validator::make($data, $rules)->validate();

        return $note;
    }
}
