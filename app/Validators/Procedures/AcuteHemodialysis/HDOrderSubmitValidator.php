<?php

namespace App\Validators\Procedures\AcuteHemodialysis;

use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HDOrderSubmitValidator
{
    use OrderFormConfigsShareable;

    public function __invoke(array $data)
    {
        return Validator::make($data, [
            // 'access_type' => *REQUIRED*|string|in[...],
            'access_type' => ['required', Rule::in($this->FORM_CONFIGS['access_types'])],
            // 'access_site_coagulant' => *REQUIRED*|string|in[...], // if access_type == 'pending', must be null
            'access_site_coagulant' => [
                'prohibited_if:access_type,pending',
                Rule::requiredIf(fn () => $data['access_type'] !== 'pending'),
                Rule::in(str_starts_with($data['access_type'], 'AV') ? $this->FORM_CONFIGS['av_access_sites'] : $this->FORM_CONFIGS['non_av_access_sites']),
            ],
            // 'dialyzer' => *REQUIRED*|string|in[...],
            'dialyzer' => ['required', Rule::in($this->FORM_CONFIGS['dialyzers'])],
            // 'dialysate' => *REQUIRED*|string|in[...],
            'dialysate' => ['required', Rule::in($this->FORM_CONFIGS['dialysates'])],
            // 'dialysate_flow_rate' => *REQUIRED*|integer|in[...],
            'dialysate_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
            // 'reverse_dialysate_flow' => *REQUIRED*|bool,
            'reverse_dialysate_flow' => 'required|boolean',
            // 'blood_flow_rate' => *REQUIRED*|integer|in[...],
            'blood_flow_rate' => ['required', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
            // 'dialysate_temperature' => *REQUIRED*|integer|in[...],
            'dialysate_temperature' => ['required', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
            // 'sodium' => *REQUIRED*|integer|[128-145], // default 138
            'sodium' => ['required', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['sodium']['min'].','.$this->FORM_CONFIGS['validators']['sodium']['max']],
            // 'sodium_profile' => *REQUIRED*|bool,
            'sodium_profile' => 'required|boolean',
            // 'sodium_profile_start' => if sodium_profile|integer,
            'sodium_profile_start' => 'required_if:sodium_profile,true|integer',
            // 'sodium_profile_end' => if sodium_profile|integer,
            'sodium_profile_end' =>  [Rule::requiredIf(fn () => $data['sodium_profile_start']), 'integer'],
            // 'bicarbonate' => *REQUIRED*|integer|in[...],
            'bicarbonate' => ['required', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
            // 'anticoagulant' => *REQUIRED*|string,
            'anticoagulant' => 'required|string|max:255',
            // 'anticoagulant_none_drip_via_peripheral_iv' => *REQUIRED*|bool,
            'anticoagulant_none_drip_via_peripheral_iv' => 'required|boolean',
            // 'anticoagulant_none_nss_200ml_flush_q_hour' => *REQUIRED*|bool,
            'anticoagulant_none_nss_200ml_flush_q_hour' => 'required|boolean',
            // 'heparin_loading_dose' => if anticoagulant == heparin|integer|[250,2000],
            'heparin_loading_dose' => ['required_if:anticoagulant,heparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_loading_dose']['max']],
            // 'heparin_maintenance_dose' => if anticoagulant == heparin|integer|[0,1500],
            'heparin_maintenance_dose' => ['required_if:anticoagulant,heparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['min'].','.$this->FORM_CONFIGS['validators']['heparin_maintenance_dose']['max']],
            // 'enoxaparin_dose' => if anticoagulant == enoxaparin|number|[0.3,0.8],
            'enoxaparin_dose' => ['required_if:anticoagulant,enoxaparin', 'nullable', 'numeric', 'between:'.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['enoxaparin_dose']['max']],
            // 'fondaparinux_bolus_dose' => if anticoagulant == fondaparinux|integer|in[...],
            'fondaparinux_bolus_dose' => ['required_if:anticoagulant,fondaparinux', 'nullable', Rule::in($this->FORM_CONFIGS['fondaparinux_bolus_doses'])],
            // 'tinzaparin_dose' => if anticoagulant == tinzaparin|integer|[0,1500],
            'tinzaparin_dose' => ['required_if:anticoagulant,tinzaparin', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['min'].','.$this->FORM_CONFIGS['validators']['tinzaparin_dose']['max']],
            // 'ultrafiltration_min' => if dry_weight == null|integer|[0,5500],
            'ultrafiltration_min' => ['required_if:dry_weight,null', 'nullable', 'integer', 'between:'.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_min']['max']],
            // 'ultrafiltration_max' => nullable|integer|[ultrafiltration_min,5500],
            'ultrafiltration_max' => [Rule::requiredIf(fn () => $data['ultrafiltration_min']), 'nullable', 'integer', 'between:'.$data['ultrafiltration_min'].','.$this->FORM_CONFIGS['validators']['ultrafiltration_max']['max']],
            // 'dry_weight' => if ultrafiltration_min == null|number,
            'dry_weight' => 'required_if:ultrafiltration_min,null|numeric',
            // 'glucose_50_percent_iv_volume' => nullable|integer|in[...],
            'glucose_50_percent_iv_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_volumes'])],
            // 'glucose_50_percent_iv_at' => nullable|integer|in[...],
            'glucose_50_percent_iv_at' => ['nullable', Rule::in($this->FORM_CONFIGS['glucose_50_percent_iv_at'])],
            // 'albumin_20_percent_prime' => nullable|integer|in[...],
            'albumin_20_percent_prime' => ['nullable', Rule::in($this->FORM_CONFIGS['albumin_20_percent_primes'])],
            // 'nutrition_iv_type' => nullable|string|max:255,
            'nutrition_iv_type' => 'nullable|string|max:255',
            // 'nutrition_iv_volume' => nullable|integer,
            'nutrition_iv_volume' => 'nullable|integer',
            // 'post_dialysis_bw' => *REQUIRED*|bool,
            'post_dialysis_bw' => 'required|boolean',
            // 'prc_volume' => nullable|integer,
            'prc_volume' => 'nullable|integer',
            // 'ffp_volume' => nullable|integer,
            'ffp_volume' => 'nullable|integer',
            // 'platelet_volume' => nullable|integer,
            'platelet_volume' => 'nullable|integer',
            // 'transfusion_other' => nullable|string|max:255,
            'transfusion_other' => 'nullable|string|max:255',
        ])->validate();
    }
}
