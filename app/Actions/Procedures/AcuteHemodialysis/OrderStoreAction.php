<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use App\Rules\HashedKeyExistsInCaseRecords;
use App\Rules\NameExistsInAttendingStaff;
use App\Rules\NameExistsInWards;
use App\Traits\AcuteHemodialysis\OrderShareValidatable;
use App\Traits\AcuteHemodialysis\SwapCodeGeneratable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderStoreAction extends AcuteHemodialysisAction
{
    use OrderShareValidatable, SwapCodeGeneratable;

    protected $FORM_VERSION = 1.0;

    protected $BASE_FORM_TEMPLATE = [
        'hemodynamic' => [
            'stable' => false,
            'hypotention' => false,
            'inotropic_dependent' => false,
            'severe_hypertension' => false,
            'bradycardia' => false,
            'arrhythmia' => false,
        ],
        'respiration' => [
            'stable' => false,
            'hypoxia' => false,
            'high_risk_airway_obstruction' => false,
        ],
        'oxygen_support' => 'None',
        'neurological' => [
            'stable' => false,
            'gcs_drop' => false,
            'drowsiness' => false,
        ],
        'life_threatening_condition' => [
            'stable' => false,
            'acute_coronary_syndrome' => false,
            'cardiac_arrhymia_with_hypotension' => false,
            'acute_ischemic_stroke' => false,
            'acute_ich' => false,
            'seizure' => false,
            'cardiac_arrest' => false,
        ],
        'monitor' => [
            'standard' => false,
            'ekg' => false,
            'observe_chest_pain' => false,
            'observe_neuro_sign' => false,
            'other' => null,
        ],
        'predialysis_labs_request' => false,
        'postdialysis_esa' => false,
        'postdialysis_iron_iv' => false,
        'treatments_request' => null,
    ];

    protected $HD_FORM_TEMPLATE = [
        'access_type' => null,
        'access_site_coagulant' => null,
        'dialyzer' => null,
        'dialysate' => null,
        'dialysate_flow_rate' => null,
        'reverse_dialysate_flow' => false,
        'blood_flow_rate' => null,
        'dialysate_temperature' => null,
        'sodium' => '138',
        'sodium_profile' => false,
        'sodium_profile_start' => null,
        'sodium_profile_end' => null,
        'bicarbonate' => null,
        'anticoagulant' => null,
        'anticoagulant_none_drip_via_peripheral_iv' => false,
        'anticoagulant_none_nss_200ml_flush_q_hour' => false,
        'heparin_loading_dose' => null,
        'heparin_maintenance_dose' => null,
        'enoxaparin_dose' => null,
        'fondaparinux_bolus_dose' => null,
        'tinzaparin_dose' => null,
        'ultrafiltration_min' => null,
        'ultrafiltration_max' => null,
        'dry_weight' => null,
        'glucose_50_percent_iv_volume' => null,
        'glucose_50_percent_iv_at' => null,
        'albumin_20_percent_prime' => null,
        'nutrition_iv_type' => null,
        'nutrition_iv_volume' => null,
        'post_dialysis_bw' => false,
        'prc_volume' => null,
        'ffp_volume' => null,
        'platelet_volume' => null,
        'transfusion_other' => null,
    ];

    protected $HF_FORM_TEMPLATE = [
        'access_type' => null,
        'access_site_coagulant' => null,
        'dialyzer' => null,
        'blood_flow_rate' => null,
        'anticoagulant' => null,
        'anticoagulant_none_drip_via_peripheral_iv' => false,
        'anticoagulant_none_nss_200ml_flush_q_hour' => false,
        'heparin_loading_dose' => null,
        'heparin_maintenance_dose' => null,
        'enoxaparin_dose' => null,
        'fondaparinux_bolus_dose' => null,
        'tinzaparin_dose' => null,
        'ultrafiltration_min' => null,
        'ultrafiltration_max' => null,
        'dry_weight' => null,
        'glucose_50_percent_iv_volume' => null,
        'albumin_20_percent_prime' => null,
        'nutrition_iv_type' => null,
        'nutrition_iv_volume' => null,
        'prc_volume' => null,
        'ffp_volume' => null,
        'platelet_volume' => null,
        'transfusion_other' => null,
    ];

    protected $TPE_FORM_TEMPLATE = [
        'access_type' => null,
        'access_site_coagulant' => null,
        'dialyzer' => 'Plasmaflo',
        'replacement_fluid_albumin' => false,
        'replacement_fluid_albumin_concentrated' => null,
        'replacement_fluid_albumin_volume' => null,
        'replacement_fluid_ffp' => false,
        'replacement_fluid_ffp_volume' => null,
        'blood_pumb' => 150,
        'filtration_pumb' => 30,
        'replacement_pumb' => 30,
        'drain_pumb' => null,
        'calcium_gluconate_10_percent_volume' => null,
        'calcium_gluconate_10_percent_timing' => null,
    ];

    protected $SLEDD_FORM_TEMPLATE = [
        'duration' => null,
        'access_type' => null,
        'access_site_coagulant' => null,
        'dialyzer' => 'SF150E',
        'dialysate' => null,
        'blood_flow_rate' => 200,
        'dialysate_flow_rate' => 300,
        'reverse_dialysate_flow' => false,
        'dialysate_temperature' => null,
        'sodium' => '138',
        'sodium_profile' => false,
        'sodium_profile_start' => null,
        'sodium_profile_end' => null,
        'bicarbonate' => null,
        'anticoagulant' => null,
        'anticoagulant_none_drip_via_peripheral_iv' => false,
        'anticoagulant_none_nss_200ml_flush_q_hour' => false,
        'heparin_loading_dose' => null,
        'heparin_maintenance_dose' => null,
        'enoxaparin_dose' => null,
        'fondaparinux_bolus_dose' => null,
        'tinzaparin_dose' => null,
        'anticoagulant_other' => null,
        'ultrafiltration_min' => null,
        'ultrafiltration_max' => null,
        'dry_weight' => null,
        'glucose_50_percent_iv_volume' => null,
        'glucose_50_percent_iv_at' => null,
        'albumin_20_percent_prime' => null,
        'nutrition_iv_type' => null,
        'nutrition_iv_volume' => null,
        'post_dialysis_bw' => false,
        'prc_volume' => null,
        'ffp_volume' => null,
        'platelet_volume' => null,
        'transfusion_other' => null,
        'remark' => null,
    ];

    /**
     * @todo recheck date_note+dialysis_type+dialysis_at against available slots
     * @todo authorize action
     */
    public function __invoke(array $data, User $user): mixed
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $cacheKeyPrefix = $user->login;

        $validated = Validator::make($data, [
            'dialysis_type' => ['required', 'string', Rule::in($this->getAllDialysisType())],
            'patient_type' => ['required', 'string', Rule::in($this->PATIENT_TYPES)],
            'dialysis_at' => ['required', 'string', 'max:255', new NameExistsInWards($cacheKeyPrefix)],
            'attending_staff' => ['required', 'string', 'max:255', new NameExistsInAttendingStaff($cacheKeyPrefix)],
            'case_record_hashed_key' => ['required', new HashedKeyExistsInCaseRecords($cacheKeyPrefix)],
            'date_note' => ['required', 'date'],
        ])->validate();

        $caseRecord = cache()->pull($cacheKeyPrefix.'-validatedCaseRecord');
        if (! $this->isDialysisReservable($caseRecord)) {
            throw ValidationException::withMessages(['status' => 'one active order at a time']);
        }

        $ensureSlotAvailable = (new SlotAvailableAction)($validated);
        if (! $ensureSlotAvailable['available']) {
            throw ValidationException::withMessages(['status' => 'no slot available']);
        }

        $note = new AcuteHemodialysisOrderNote();
        $note->case_record_id = $caseRecord->id;
        $note->attending_staff_id = cache()->pull($cacheKeyPrefix.'-validatedAttending')->id;
        $note->place_type = Ward::class;
        $ward = cache()->pull($cacheKeyPrefix.'-validatedWard');
        $note->place_id = $ward->id;
        $note->date_note = $validated['date_note'];
        $form = $this->initForm($validated['dialysis_type']);
        $note->form = $form;
        $patient = $caseRecord->patient;
        $note->meta = [
            'hn' => $patient->hn,
            'name' => $patient->first_name,
            'version' => $this->FORM_VERSION,
            'in_unit' => $ward->id === $this->IN_UNIT_WARD_ID,
            'patient_type' => $validated['patient_type'],
            'dialysis_type' => $validated['dialysis_type'],
            'swap_code' => $this->genSwapCode(),
        ];
        $note->user_id = $user->id;
        $note->save();

        return $note;
    }

    protected function initForm(string $dialysisType): array
    {
        $form = $this->BASE_FORM_TEMPLATE;
        $dialysisType = Str::of($dialysisType);

        if ($dialysisType->contains('HD+HF')) {
            $form['hd'] = $this->HD_FORM_TEMPLATE;
            $form['hd']['hf_perform_at'] = null;
            $form['hd']['hf_ultrafiltration_min'] = null;
            $form['hd']['hf_ultrafiltration_max'] = null;
        } elseif ($dialysisType->contains('HD+TPE')) {
            $form['hd'] = $this->HD_FORM_TEMPLATE;
            $form['tpe'] = $this->TPE_FORM_TEMPLATE;
        } elseif ($dialysisType->contains('HD ')) {
            $form['hd'] = $this->HD_FORM_TEMPLATE;
        } elseif ($dialysisType->contains('HF ')) {
            $form['hf'] = $this->HF_FORM_TEMPLATE;
        } elseif ($dialysisType->contains('TPE ')) {
            $form['tpe'] = $this->TPE_FORM_TEMPLATE;
        } elseif ($dialysisType->contains('SLEDD')) {
            $form['sledd'] = $this->SLEDD_FORM_TEMPLATE;
        }

        return $form;
    }
}
