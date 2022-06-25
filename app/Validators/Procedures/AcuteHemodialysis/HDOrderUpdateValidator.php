<?php

namespace App\Validators\Procedures\AcuteHemodialysis;

use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HDOrderUpdateValidator
{
    use OrderFormConfigsShareable;

    public function __invoke(array $data)
    {
        return Validator::make($data, [
            'access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
            'access_site_coagulant' => ['nullable', 'string', 'max:30'],
            'dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['dialyzers'])],
            'dialysate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysates'])],
            'dialysate_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
            'reverse_dialysate_flow' => 'boolean',
            'blood_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
            'dialysate_temperature' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
            'sodium' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'sodium_profile' => 'boolean',
            'sodium_profile_start' => 'nullable|integer',
            'sodium_profile_end' => 'nullable|integer',
            'bicarbonate' => ['nullable', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
            'anticoagulant' => 'nullable|string|max:255',
            'anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
            'anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
            'heparin_loading_dose' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'heparin_maintenance_dose' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'enoxaparin_dose' => 'nullable|numeric', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'fondaparinux_bolus_dose' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'tinzaparin_dose' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'ultrafiltration_min' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'ultrafiltration_mix' => 'nullable|integer', // autosave trigger on typing so, move |min:xxx|max:xxx to completeness validation
            'dry_weight' => 'nullable|numeric',
            'glucose_50_percent_iv_volume' => 'nullable|integer',
            'glucose_50_percent_iv_at' => 'nullable|integer',
            'albumin_20_percent_prime' => 'nullable|integer',
            'nutrition_iv_type' => 'nullable|string|max:255',
            'nutrition_iv_volume' => 'nullable|integer',
            'post_dialysis_bw' => 'boolean',
            'prc_volume' => 'nullable|integer',
            'ffp_volume' => 'nullable|integer',
            'platelet_volume' => 'nullable|integer',
            'transfusion_other' => 'nullable|string|max:255',
        ])->validate();
    }
}
