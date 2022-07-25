<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use App\Traits\AcuteHemodialysis\OrderFormConfigsShareable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderUpdateAction extends AcuteHemodialysisAction
{
    use OrderFormConfigsShareable;

    /**
     * Should validate by dialysis type.
     *
     * @todo  validate form before update
     */
    public function __invoke(array $data, string $hashedKey, User $user)
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
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
            ]);
        }

        if (isset($data['tpe'])) {
            $rules = array_merge($rules, [
                'tpe.access_type' => ['nullable', Rule::in($this->FORM_CONFIGS['access_types'])],
                'tpe.access_site_coagulant' => 'nullable|string|max:30',
                'tpe.dialyzer' => ['nullable', Rule::in($this->FORM_CONFIGS['tpe_dialyzers'])],
                'tpe.replacement_fluid_albumin' => 'boolean',
                'tpe.replacement_fluid_albumin_concentrated' => ['nullable', Rule::in($this->FORM_CONFIGS['replacement_fluid_albumin_concentrated'])],
                'tpe.replacement_fluid_albumin_volume' => 'nullable|integer',
                'tpe.replacement_fluid_ffp' => 'boolean',
                'tpe.replacement_fluid_ffp_volume' => 'nullable|integer',
                'tpe.blood_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['blood_pump'])],
                'tpe.filtration_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['tpe_filtration_pump_options'])],
                'tpe.replacement_pump' => ['nullable', Rule::in($this->FORM_CONFIGS['tpe_filtration_pump_options'])],
                'tpe.drain_pump' => 'nullable|integer',
                'tpe.calcium_gluconate_10_percent_volume' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_volumes'])],
                'tpe.calcium_gluconate_10_percent_timing' => ['nullable', Rule::in($this->FORM_CONFIGS['calcium_gluconate_10_percent_timings'])],
                'tpe.anticoagulant' => 'nullable|string|max:255',
                'tpe.anticoagulant_none_drip_via_peripheral_iv' => 'boolean',
                'tpe.anticoagulant_none_nss_200ml_flush_q_hour' => 'boolean',
                'tpe.heparin_loading_dose' => 'nullable|integer',
                'tpe.heparin_maintenance_dose' => 'nullable|integer',
                'tpe.enoxaparin_dose' => 'nullable|numeric',
                'tpe.fondaparinux_bolus_dose' => 'nullable|integer',
                'tpe.tinzaparin_dose' => 'nullable|integer',
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

        return $note->save();
    }
}
