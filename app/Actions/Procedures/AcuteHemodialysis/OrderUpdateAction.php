<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderUpdateAction extends AcuteHemodialysisAction
{
    use AvatarLinkable, OrderFormConfigsShareable;

    public function __invoke(array $data, string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $note = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('update', $note)) {
            abort(403);
        }

        $rules = [];
        if (isset($data['hd'])) {
            if (strpos($note->meta['dialysis_type'], '+HF')) {
                $rules = array_merge($rules, [
                    'hd.hf_perform_at' => ['nullable', Rule::in($this->FORM_CONFIGS['hf_perform_at'])],
                    'hd.hf_ultrafiltration_min' => 'nullable|integer',
                    'hd.hf_ultrafiltration_max' => 'nullable|integer',
                ]);
            }
            $rules = array_merge($rules, [
                'hd.access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
                'hd.access_site_coagulant' => ['nullable', 'string', 'max:30'],
                'hd.dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                'hd.dialysate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysates'])],
                'hd.dialysate_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
                'hd.reverse_dialysate_flow' => 'boolean',
                'hd.blood_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'hd.dialysate_temperature' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
                'hd.sodium' => 'nullable|integer',
                'hd.sodium_profile' => 'boolean',
                'hd.sodium_profile_start' => 'nullable|integer',
                'hd.sodium_profile_end' => 'nullable|integer',
                'hd.bicarbonate' => ['nullable', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
                'hd.anticoagulant' => 'nullable|string|max:255',
                'hd.anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
                'hd.anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
                'hd.heparin_loading_dose' => 'nullable|integer',
                'hd.heparin_maintenance_dose' => 'nullable|integer',
                'hd.enoxaparin_dose' => 'nullable|numeric',
                'hd.fondaparinux_bolus_dose' => 'nullable|integer',
                'hd.tinzaparin_dose' => 'nullable|integer',
                'hd.ultrafiltration_min' => 'nullable|integer',
                'hd.ultrafiltration_max' => 'nullable|integer',
                'hd.dry_weight' => 'nullable|numeric',
                'hd.glucose_50_percent_iv_volume' => 'nullable|integer',
                'hd.glucose_50_percent_iv_at' => 'nullable|integer',
                'hd.albumin_20_percent_prime' => 'nullable|integer',
                'hd.nutrition_iv_type' => 'nullable|string|max:255',
                'hd.nutrition_iv_volume' => 'nullable|integer',
                'hd.prc_volume' => 'nullable|numeric',
                'hd.ffp_volume' => 'nullable|integer',
                'hd.platelet_volume' => 'nullable|numeric',
                'hd.transfusion_other' => 'nullable|string|max:255',
                'hd.catheter_lock' => 'nullable|string|max:255',
            ]);
        }

        if (isset($data['hf'])) {
            $rules = array_merge($rules, [
                'hf.access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
                'hf.access_site_coagulant' => ['nullable', 'string', 'max:30'],
                'hf.dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['hf_dialyzers'])],
                'hf.blood_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'hf.anticoagulant' => 'nullable|string|max:255',
                'hf.anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
                'hf.anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
                'hf.heparin_loading_dose' => 'nullable|integer',
                'hf.heparin_maintenance_dose' => 'nullable|integer',
                'hf.enoxaparin_dose' => 'nullable|numeric',
                'hf.fondaparinux_bolus_dose' => 'nullable|integer',
                'hf.tinzaparin_dose' => 'nullable|integer',
                'hf.ultrafiltration_min' => 'nullable|integer',
                'hf.ultrafiltration_max' => 'nullable|integer',
                'hf.dry_weight' => 'nullable|numeric',
                'hf.glucose_50_percent_iv_volume' => 'nullable|integer',
                'hf.albumin_20_percent_prime' => 'nullable|integer',
                'hf.nutrition_iv_type' => 'nullable|string|max:255',
                'hf.nutrition_iv_volume' => 'nullable|integer',
                'hf.prc_volume' => 'nullable|numeric',
                'hf.ffp_volume' => 'nullable|integer',
                'hf.platelet_volume' => 'nullable|numeric',
                'hf.transfusion_other' => 'nullable|string|max:255',
                'hf.catheter_lock' => 'nullable|string|max:255',
            ]);
        }

        if (isset($data['pe'])) {
            $rules = array_merge($rules, [
                'pe.technique' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_technique_options'])],
                'pe.access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
                'pe.access_site_coagulant' => 'nullable|string|max:30',
                'pe.dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_dialyzers'])],
                'pe.dialyzer_second' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_dialyzer_second_options'])],
                'pe.replacement_fluid_albumin' => 'boolean',
                'pe.replacement_fluid_albumin_concentrated' => ['nullable', Rule::in($this->FORM_CONFIGS['replacement_fluid_albumin_concentrated'])],
                'pe.replacement_fluid_albumin_volume' => 'nullable|integer',
                'pe.replacement_fluid_ffp' => 'boolean',
                'pe.replacement_fluid_ffp_volume' => 'nullable|integer',
                'pe.blood_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_pump'])],
                'pe.filtration_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_filtration_pump_options'])],
                'pe.replacement_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_replacement_pump_options'])],
                'pe.percent_discard' => ['nullable', Rule::in($this->FORM_CONFIGS['pe_percent_discard_options'])],
                'pe.drain_pump' => 'nullable|integer',
                'pe.calcium_gluconate_10_percent_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_volumes'])],
                'pe.calcium_gluconate_10_percent_timing' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_timings'])],
                'pe.anticoagulant' => 'nullable|string|max:255',
                'pe.anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
                'pe.anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
                'pe.heparin_loading_dose' => 'nullable|integer',
                'pe.heparin_maintenance_dose' => 'nullable|integer',
                'pe.enoxaparin_dose' => 'nullable|numeric',
                'pe.fondaparinux_bolus_dose' => 'nullable|integer',
                'pe.tinzaparin_dose' => 'nullable|integer',
                'pe.catheter_lock' => 'nullable|string|max:255',
            ]);
        }

        if (isset($data['sledd'])) {
            $rules = array_merge($rules, [
                'sledd.duration' => ['nullable', Rule::in($this->FORM_CONFIGS['sledd_durations'])],
                'sledd.access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
                'sledd.access_site_coagulant' => ['nullable', 'string', 'max:30'],
                'sledd.dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['dialyzers'])],
                'sledd.dialysate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysates'])],
                'sledd.dialysate_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_flow_rates'])],
                'sledd.reverse_dialysate_flow' => 'boolean',
                'sledd.blood_flow_rate' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_flow_rates'])],
                'sledd.dialysate_temperature' => ['nullable', Rule::in($this->FORM_CONFIGS['dialysate_temperatures'])],
                'sledd.sodium' => 'nullable|integer',
                'sledd.sodium_profile' => 'boolean',
                'sledd.sodium_profile_start' => 'nullable|integer',
                'sledd.sodium_profile_end' => 'nullable|integer',
                'sledd.bicarbonate' => ['nullable', Rule::in($this->FORM_CONFIGS['bicarbonates'])],
                'sledd.anticoagulant' => 'nullable|string|max:255',
                'sledd.anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
                'sledd.anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
                'sledd.heparin_loading_dose' => 'nullable|integer',
                'sledd.heparin_maintenance_dose' => 'nullable|integer',
                'sledd.enoxaparin_dose' => 'nullable|numeric',
                'sledd.fondaparinux_bolus_dose' => 'nullable|integer',
                'sledd.tinzaparin_dose' => 'nullable|integer',
                'sledd.ultrafiltration_min' => 'nullable|integer',
                'sledd.ultrafiltration_max' => 'nullable|integer',
                'sledd.dry_weight' => 'nullable|numeric',
                'sledd.glucose_50_percent_iv_volume' => 'nullable|integer',
                'sledd.glucose_50_percent_iv_at' => 'nullable|integer',
                'sledd.albumin_20_percent_prime' => 'nullable|integer',
                'sledd.nutrition_iv_type' => 'nullable|string|max:255',
                'sledd.nutrition_iv_volume' => 'nullable|integer',
                'sledd.prc_volume' => 'nullable|numeric',
                'sledd.ffp_volume' => 'nullable|integer',
                'sledd.platelet_volume' => 'nullable|numeric',
                'sledd.transfusion_other' => 'nullable|string|max:255',
                'sledd.catheter_lock' => 'nullable|string|max:255',
                'sledd.remark' => 'nullable|string|max:255',
            ]);
        }

        // base
        $rules = array_merge($rules, [
            'hemodynamic.stable' => 'boolean',
            'hemodynamic.hypotension' => 'boolean',
            'hemodynamic.inotropic_dependent' => 'boolean',
            'hemodynamic.severe_hypertension' => 'boolean',
            'hemodynamic.bradycardia' => 'boolean',
            'hemodynamic.arrhythmia' => 'boolean',
            'respiration.stable' => 'boolean',
            'respiration.hypoxia' => 'boolean',
            'respiration.high_risk_airway_obstruction' => 'boolean',

            'oxygen_support' => ['nullable', Rule::in($this->FORM_CONFIGS['oxygen_options'])],

            'neurological.stable' => 'boolean',
            'neurological.gcs_drop' => 'boolean',
            'neurological.drowsiness' => 'boolean',

            'life_threatening_condition.stable' => 'boolean',
            'life_threatening_condition.acute_coronary_syndrome' => 'boolean',
            'life_threatening_condition.cardiac_arrhythmia_with_hypotension' => 'boolean',
            'life_threatening_condition.acute_ischemic_stroke' => 'boolean',
            'life_threatening_condition.acute_ich' => 'boolean',
            'life_threatening_condition.seizure' => 'boolean',
            'life_threatening_condition.cardiac_arrest' => 'boolean',

            'monitor.standard' => 'boolean',
            'monitor.ekg' => 'boolean',
            'monitor.observe_chest_pain' => 'boolean',
            'monitor.observe_neuro_sign' => 'boolean',
            'monitor.other' => 'nullable|string|max:255',

            'predialysis_labs_request' => 'boolean',
            'postdialysis_bw' => 'boolean',
            'postdialysis_esa' => 'boolean',
            'postdialysis_iron_iv' => 'boolean',
            'treatments_request' => 'nullable|string|max:255',
        ]);

        $validated = Validator::make($data, $rules)->validate();

        $note->form = $validated;

        return ['ok' => $note->save()];
    }
}
