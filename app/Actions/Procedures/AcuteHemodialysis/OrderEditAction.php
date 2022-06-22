<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Note;
use App\Models\Resources\Ward;
use Hashids\Hashids;

class OrderEditAction extends AcuteHemodialysisAction
{
    protected $FORM_CONFIGS = [
        'access_types' => ['DLC', 'Perm cath', 'AVF', 'AVG', 'pending'],
        'av_access_sites' => ['Rt arm', 'Lt arm', 'Rt leg', 'Lt leg'],
        'non_av_access_sites' => ['Rt IJV', 'Lt IJV', 'Rt Femoral vain', 'Lt Femoral vain', 'Rt SCV', 'Lt SCV'],
        'dialyzers' => ['SF130E', 'SF150E', 'SF170E', 'SF190E', 'Rexeed 13L', 'Rexeed 15L', 'Rexeed 18L', 'FB170U', 'FB190U', 'FB210U', 'HF80S'],
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
        ],
        'dialysate_flow_rates' => [300, 500, 800],
        'blood_flow_rates' => [200, 250, 300, 350, 400],
        'dialysate_temperatures' => [35.5, 36],
        'bicarbonates' => [28, 32, 35],
        'anticoagulants' => [
            ['value' => 'None', 'label' => 'None'],
            ['value' => 'Heparin', 'label' => 'Heparin'],
            ['value' => 'Enoxaparin', 'label' => 'Enoxaparin (Clexane®, Levenox®)'],
            ['value' => 'Fondaparinux', 'label' => 'Fondaparinux'],
            ['value' => 'Tinzaparin', 'label' => 'Tinzaparin (Innohep)'],
            ['value' => 'Other', 'label' => 'Other'],
        ],
        'hemodynamic_symptoms' => [
            ['name' => 'hypotention', 'label' => 'Hypotention'],
            ['name' => 'inotropic_dependent', 'label' => 'Inotropic dependent'],
            ['name' => 'severe_hypertension', 'label' => 'Severe hypertension (BP > 200/120 mmHg)'],
            ['name' => 'bradycardia', 'label' => 'Bradycardia (HR < 50/min)'],
            ['name' => 'arrhythmia', 'label' => 'Arrhythmia (Heart block, Tachyarrthmia)'],
        ],
        'raspiration_options' => [
            ['name' => 'hypoxia', 'label' => 'Hypoxia (O₂ sat < 95%, impeding respiration failure)'],
            ['name' => 'high_risk_airway_obstruction', 'label' => 'High risk to airway obstruction'],
        ],
        'oxygen_options' => ['None', 'Oxygen canula', 'Mask with bag', 'High flow oxygen', 'Ventilator'],
        'neurological_options' => [
            ['name' => 'gcs_drop', 'label' => 'GCS drop > 2 in the past 24 hours'],
            ['name' => 'drowsiness', 'label' => 'Drowsiness'],
        ],
        'life_threatening_condition_options' => [
            ['name' => 'acute_coronary_syndrome', 'label' => 'Acute coronary syndrome'],
            ['name' => 'cardiac_arrhymia_with_hypotension', 'label' => 'Cardiac arrhymia with hypotension'],
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
            ['name' => 'sodium', 'min' => 128, 'max' => 145, 'type' => 'integer'],
            ['name' => 'heparin_loading_dose', 'min' => 250, 'max' => 2000, 'type' => 'integer'],
            ['name' => 'heparin_maintenance_dose', 'min' => 0, 'max' => 1500, 'type' => 'integer'],
            ['name' => 'enoxaparin_dose', 'min' => 0.3, 'max' => 0.8, 'type' => 'float'],
            ['name' => 'tinzaparin_dose', 'min' => 1500, 'max' => 3500, 'type' => 'interger'],
            ['name' => 'ultrafiltration', 'min' => 0, 'max' => 5500, 'type' => 'interger'],
            ['name' => 'ultrafiltration_hf', 'min' => 0, 'max' => 4000, 'type' => 'interger'],
            ['name' => 'glucose_50_percent_iv_volume', 'min' => 50, 'max' => 100, 'type' => 'interger'],
        ],
        'tpe_dialyzers' => ['Plasmaflo'],
        'tpe_filtration_pumb_options' => [20, 25, 30, 40],
    ];

    public function __invoke(string $hashedKey)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = Note::query()->withPlaceName(Ward::class)->findByUnhashKey($hashedKey)->firstOrFail();

        $form = $note->form;
        $form['reservation'] = [
            'hn' => $note->meta['hn'],
            'an' => $note->meta['an'] ?? null,
            'dialysis_at' => $note->place_name,
        ];

        $flash = [
            'page-title' => 'Acute HD Order '.$note->patient->profile['first_name'].' @ '.$note->date_note->format('d M Y'),
            'main-menu-links' => [
                ['icon' => 'arrow-circle-left', 'label' => 'Back', 'route' => route('procedures.acute-hemodialysis.edit', app(Hashids::class)->encode($note->case_record_id)), 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Prescription', 'type' => '#', 'route' => '#prescription', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Predialysis', 'type' => '#', 'route' => '#predialysis-evaluation', 'can' => true],
                ['icon' => 'slack-hash', 'label' => 'Monitoring', 'type' => '#', 'route' => '#monitoring', 'can' => true],
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
            ],
            'action-menu' => [],
        ];

        return [
            'orderForm' => $form,
            'flash' => $flash,
            'formConfigs' => $this->FORM_CONFIGS + [
                'update_endpoint' => route('procedures.acute-hemodialysis.orders.update', $note->hashed_key),
            ],
        ];
    }
}
