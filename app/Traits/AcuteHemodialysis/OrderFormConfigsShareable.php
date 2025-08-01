<?php

namespace App\Traits\AcuteHemodialysis;

trait OrderFormConfigsShareable
{
    protected array $FORM_CONFIGS = [
        'access_types' => ['DLC', 'Perm cath', 'AVF', 'AVG', 'pending'],
        'av_access_sites' => ['Rt arm', 'Lt arm', 'Rt leg', 'Lt leg'],
        'non_av_access_sites' => ['Rt IJV', 'Lt IJV', 'Rt Femoral vain', 'Lt Femoral vain', 'Rt SCV', 'Lt SCV'],
        'dialyzers' => ['SF130E', 'SF150E', 'SF170E', 'SF190E', 'Rexeed 13L', 'Rexeed 15L', 'Rexeed 18L', 'FB170U', 'FB190U', 'FB210U', 'HF80S', 'Elisio 210 HR', 'Elisio 21 H'],
        'hf_dialyzers' => ['SF130E', 'SF150E', 'Rexeed 13L', 'Rexeed 15L'],
        'dialysates' => [
            'None => K 0, Ca 0, Mg 0',
            'K 3, Ca 3.5, Mg 1 => AA 101 K3',
            'K 3, Ca 3.0, Mg 1 => AA 301 K3',
            'K 3, Ca 2.5, Mg 1 => AA 251 K3',
            'K 3, Ca 2.0, Mg 1 => AA 201 K3',
            'K 2, Ca 3.5, Mg 1 => AA 101',
            'K 2, Ca 3.0, Mg 1 => AA 301',
            'K 2, Ca 2.5, Mg 1 => AA 251',
            'K 2, Ca 2.0, Mg 1 => AA 201',
            'K 3, Ca 3.5, Mg 0.7 => AA 101 K3',
            'K 3, Ca 3.0, Mg 0.7 => AA 301 K3',
            'K 3, Ca 2.5, Mg 0.7 => AA 251 K3',
            'K 3, Ca 2.0, Mg 0.7 => AA 201 K3',
            'K 2, Ca 3.5, Mg 0.7 => AA 101',
            'K 2, Ca 3.0, Mg 0.7 => AA 301',
            'K 2, Ca 2.5, Mg 0.7 => AA 251',
            'K 2, Ca 2.0, Mg 0.7 => AA 201',
            'K 4, Ca 2.0, Mg 1 => AA 201 K4',
            'K 4, Ca 2.5, Mg 1 => AA 251 K4',
            'K 4, Ca 3.0, Mg 1 => AA 301 K4',
            'K 4, Ca 3.5, Mg 1 => AA 101 K4',
        ],
        'dialysate_flow_rates' => [300, 500, 800],
        'blood_flow_rates' => [200, 250, 300, 350, 400],
        'dialysate_temperatures' => [35.5, 36],
        'bicarbonates' => [28, 32, 35],
        'anticoagulants' => [
            ['value' => 'none', 'label' => 'None'],
            ['value' => 'heparin', 'label' => 'Heparin'],
            ['value' => 'enoxaparin', 'label' => 'Enoxaparin (Clexane®, Levenox®)'],
            ['value' => 'fondaparinux', 'label' => 'Fondaparinux'],
            ['value' => 'tinzaparin', 'label' => 'Tinzaparin (Innohep)'],
            ['value' => 'other', 'label' => 'Other'],
        ],
        'catheter_lock_options' => [
            ['label' => 'Heparin 1:4', 'value' => 'Heparin 1:4'],
            ['label' => 'Heparin 1:1', 'value' => 'Heparin 1:1'],
            ['label' => 'Pure heparin', 'value' => 'Pure heparin'],
            ['label' => '30% citrate', 'value' => '30% citrate'],
            ['label' => 'Cefazolin 10 mg/ml + heparin 1000u/ml', 'value' => 'Cefazolin 10 mg/ml + heparin 1000u/ml'],
            ['label' => 'Ceftazidime 10 mg/ml + heparin 1000u/ml', 'value' => 'Ceftazidime 10 mg/ml + heparin 1000u/ml'],
            ['label' => 'Meropenem 10 mg/ml + heparin 1000u/ml', 'value' => 'Meropenem 10 mg/ml + heparin 1000u/ml'],
            ['label' => 'Vancomycin 5mg/ml + heparin 1000u/ml', 'value' => 'Vancomycin 5mg/ml + heparin 1000u/ml'],
            ['label' => 'Vancomycin 5 mg/ml + 4% citrate', 'value' => 'Vancomycin 5 mg/ml + 4% citrate'],
        ],
        'hemodynamic_symptoms' => [
            ['name' => 'hypotension', 'label' => 'hypotension'],
            ['name' => 'inotropic_dependent', 'label' => 'Inotropic dependent'],
            ['name' => 'severe_hypertension', 'label' => 'Severe hypertension (BP > 200/120 mmHg)'],
            ['name' => 'bradycardia', 'label' => 'Bradycardia (HR < 50/min)'],
            ['name' => 'arrhythmia', 'label' => 'Arrhythmia (Heart block, Tachyarrhythmia)'],
        ],
        'respiration_options' => [
            ['name' => 'hypoxia', 'label' => 'Hypoxia (O₂ sat < 95%, impeding respiration failure)'],
            ['name' => 'high_risk_airway_obstruction', 'label' => 'High risk to airway obstruction'],
        ],
        'oxygen_options' => ['None', 'Oxygen cannula', 'Mask with bag', 'High flow oxygen', 'Ventilator'],
        'neurological_options' => [
            ['name' => 'gcs_drop', 'label' => 'GCS drop > 2 in the past 24 hours'],
            ['name' => 'drowsiness', 'label' => 'Drowsiness'],
        ],
        'life_threatening_condition_options' => [
            ['name' => 'acute_coronary_syndrome', 'label' => 'Acute coronary syndrome'],
            ['name' => 'cardiac_arrhythmia_with_hypotension', 'label' => 'Cardiac arrhythmia with hypotension'],
            ['name' => 'acute_ischemic_stroke', 'label' => 'Acute ischemic stroke'],
            ['name' => 'acute_ich', 'label' => 'Acute ICH'],
            ['name' => 'seizure', 'label' => 'Seizure'],
            ['name' => 'cardiac_arrest', 'label' => 'Cardiac arrest'],
        ],
        'monitors' => [
            ['name' => 'ekg', 'label' => 'EKG'],
            ['name' => 'observe_chest_pain', 'label' => 'Observe chest pain'],
            ['name' => 'observe_neuro_sign', 'label' => 'Observe neuro sign'],
        ],
        'validators' => [
            'sodium' => ['min' => 128, 'max' => 145, 'type' => 'integer'],
            'heparin_loading_dose' => ['min' => 250, 'max' => 2000, 'type' => 'integer'],
            'heparin_maintenance_dose' => ['min' => 0, 'max' => 1500, 'type' => 'integer'],
            'enoxaparin_dose' => ['min' => 0.3, 'max' => 0.8, 'type' => 'float'],
            'tinzaparin_dose' => ['min' => 1500, 'max' => 3500, 'type' => 'integer'],
            'ultrafiltration_min' => ['min' => 0, 'max' => 5500, 'type' => 'integer'],
            'ultrafiltration_max' => ['min' => 0, 'max' => 5500, 'type' => 'integer'],
            'hf_ultrafiltration_min' => ['min' => 0, 'max' => 5500, 'type' => 'integer'],
            'hf_ultrafiltration_max' => ['min' => 0, 'max' => 5500, 'type' => 'integer'],
            'ultrafiltration_hf' => ['min' => 0, 'max' => 4000, 'type' => 'integer'],
            'glucose_50_percent_iv_volume' => ['min' => 50, 'max' => 100, 'type' => 'integer'],
        ],
        'fondaparinux_bolus_doses' => [500, 750],
        'pe_technique_options' => ['TPE', 'DFPP'],
        'pe_dialyzers' => ['Plasmaflo'],
        'pe_dialyzer_second_options' => ['EC 20 W', 'EC 30 W', 'EC 40 W', 'EC 50 W'],
        'pe_filtration_pump_options' => [20, 25, 30, 40],
        'pe_replacement_pump_options' => [80, 90, 100, 110, 120],
        'pe_percent_discard_options' => [15, 20],
        'glucose_50_percent_iv_volumes' => [50, 100],
        'glucose_50_percent_iv_at' => [1, 2, 3, 4],
        'albumin_20_percent_primes' => [50, 100],
        'hf_perform_at' => ['Pre HD', 'Post HD'],
        'replacement_fluid_albumin_concentrated' => [3, 4],
        'blood_pump' => [150, 200],
        'calcium_gluconate_10_percent_volumes' => [10, 20, 30],
        'calcium_gluconate_10_percent_timings' => [1, 2],
        'sledd_durations' => [6, 8],
        'sledd_dialyzers' => ['SF150E'],
        'sledd_blood_flow_rates' => [200],
        'sledd_dialysate_flow_rates' => [300],
    ];
}
