<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Actions\Resources\PatientRecentlyAdmissionAction;
use App\Extensions\Auth\AvatarUser;
use App\Managers\Resources\AdmissionManager;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use ArrayObject;

class OrderShowAction extends AcuteHemodialysisAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()
            ->withAuthorName()
            ->withPlaceName('App\Models\Resources\Ward')
            ->withAttendingName()
            ->findByUnhashKey($hashedKey)
            ->firstOrFail();

        if ($user->cannot('view', $order)) {
            abort(403);
        }

        $flash = $this->initOrderFlash($order, $user);

        if ($order->status === 'draft' || $order->status === 'scheduling') {
            $flash['message'] = [
                'type' => 'warning',
                'title' => 'The order is INCOMPLETE',
                'message' => '',
            ];
        }

        $location = $this->getPatientLocation($order);

        /* @TODO update an if null */
        $content = [
            'reservation' => [
                'hn' => $order->meta['hn'],
                'an' => $order->meta['an'] ?? 'No active AN',
                'patient location' => $location ?? 'ER ?',
                'dialysis at' => $order->place_name,
                'dialysis type' => $order->meta['dialysis_type'],
                'patient type' => $order->meta['patient_type'],
                'md' => $order->author_name,
                'attending' => $order->attending_name,
            ],
            'special_requests' => $this->getSpecialRequest($order->form),
            'predialysis_evaluation' => $this->getPredialysisEvaluation($order->form),
        ];

        foreach (['hd', 'hf', 'tpe', 'sledd'] as $type) {
            if (isset($order->form[$type])) {
                $prescription = $order->form[$type];
                if (! isset($prescription['duration'])) {
                    if (str_contains($order->meta['dialysis_type'], 6)) {
                        $prescription['duration'] = 6;
                    } elseif (str_contains($order->meta['dialysis_type'], 4)) {
                        $prescription['duration'] = 4;
                    } elseif (str_contains($order->meta['dialysis_type'], 3)) {
                        $prescription['duration'] = 3;
                    } elseif (str_contains($order->meta['dialysis_type'], 2)) {
                        $prescription['duration'] = 2;
                    }
                }
                $content[$type] = $this->getPrescription($prescription);
            }
        }

        if (! cache()->pull('no-view-log-uid-'.$user->id)) {
            $order->actionLogs()->create([
                'action' => 'view',
                'actor_id' => $user->id,
            ]);
        }

        return [
            'flash' => $flash,
            'content' => $content,
            'configs' => [
                'serology' => $this->getSerology($order->caseRecord->form->toArray()),
                'covid' => [
                    'hn' => $order->patient->hn,
                    'cid' => $order->patient->profile['document_id'],
                    'route_lab' => route('resources.api.covid-lab'),
                    'route_vaccine' => route('resources.api.covid-vaccine'),
                ],
                'session' => [
                    'dialysis_at_chronic_unit' => $order->meta['dialysis_at_chronic_unit'] ?? false,
                    'extra_slot' => $order->meta['extra_slot'] ?? false,
                    'started_at' => $order->meta['started_at'] ?? null,
                    'finished_at' => $order->meta['finished_at'] ?? null,
                    'status' => $order->status,
                    'hashed_key' => $order->hashed_key,
                ],
                /* @TODO refactor last 3 actions to policy */
                'can' => [
                    'start_session' => $user->can('start', $order),
                    'finish_session' => $user->can('finish', $order),
                    'edit_timestamp' => $order->status === 'finished' && $user->can('perform_acute_hemodialysis_order'),
                    'check_dialysis_at_chronic_unit' => $order->meta['in_unit'] && $user->can('perform_acute_hemodialysis_order'),
                    'change_session_data' => $user->can('perform_acute_hemodialysis_order'),
                ],
                'routes' => [
                    'start_session' => route('procedures.acute-hemodialysis.orders.start-session', $order->hashed_key),
                    'update_session' => route('procedures.acute-hemodialysis.orders.update-session', $order->hashed_key),
                    'finish_session' => route('procedures.acute-hemodialysis.orders.finish-session', $order->hashed_key),
                ],
                'comment' => $this->getCommentRoutes($order),
            ],
        ];
    }

    protected function getSpecialRequest(ArrayObject $form): array
    {
        $requests = collect([
            ['label' => 'Predialysis labs', 'name' => 'predialysis_labs_request'],
            ['label' => 'Postdialysis BW', 'name' => 'postdialysis_bw'],
            ['label' => 'Postdialysis ESA', 'name' => 'postdialysis_esa'],
            ['label' => 'Postdialysis Iron IV', 'name' => 'postdialysis_iron_iv'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => "<p>{$f['label']}</p>")->join('');

        return [
            ['label' => 'treatments', 'data' => $form['treatments_request'] ? collect(explode("\n", $form['treatments_request']))->transform(fn ($p) => "<p>$p</p>")->join('') : null],
            ['label' => 'requests', 'data' => $requests],
        ];
    }

    protected function getPredialysisEvaluation(ArrayObject $form): array
    {
        $content = collect([]);

        if ($form['hemodynamic']['stable']) {
            $content[] = ['label' => 'Hemodynamic', 'data' => "<p class='text-green-400'>Stable</p>"];
        } else {
            $content[] = [
                'label' => 'Hemodynamic',
                'data' => collect([
                    ['label' => 'Hypotension', 'name' => 'hypotension'],
                    ['label' => 'Inotropic dependent', 'name' => 'inotropic_dependent'],
                    ['label' => 'Severe hypertension', 'name' => 'severe_hypertension'],
                    ['label' => 'Bradycardia', 'name' => 'bradycardia'],
                    ['label' => 'Arrhythmia', 'name' => 'arrhythmia'],
                ])->filter(fn ($f) => $form['hemodynamic'][$f['name']])
                    ->values()
                    ->transform(fn ($f) => "<p class='text-red-400'>{$f['label']}</p>")
                    ->join(''),
            ];
        }

        if ($form['respiration']['stable']) {
            $content[] = ['label' => 'Respiration', 'data' => "<p class='text-green-400'>Stable</p>"];
        } else {
            $content[] = [
                'label' => 'Respiration',
                'data' => collect([
                    ['label' => 'Hypoxia', 'name' => 'hypoxia'],
                    ['label' => 'High risk airway obstruction', 'name' => 'high_risk_airway_obstruction'],
                ])->filter(fn ($f) => $form['respiration'][$f['name']])
                    ->values()
                    ->transform(fn ($f) => "<p class='text-red-400'>{$f['label']}</p>")
                    ->join(''),
            ];
        }

        if ($form['oxygen_support'] !== 'None') {
            $content[] = ['label' => 'Oxygen support', 'data' => $form['oxygen_support']];
        }

        if ($form['neurological']['stable']) {
            $content[] = ['label' => 'Neurological', 'data' => "<p class='text-green-400'>Stable</p>"];
        } else {
            $content[] = [
                'label' => 'Neurological',
                'data' => collect([
                    ['label' => 'GCS drop', 'name' => 'gcs_drop'],
                    ['label' => 'Drowsiness', 'name' => 'drowsiness'],
                ])->filter(fn ($f) => $form['neurological'][$f['name']])
                    ->values()
                    ->transform(fn ($f) => "<p class='text-red-400'>{$f['label']}</p>")
                    ->join(''),
            ];
        }

        if ($form['life_threatening_condition']['stable']) {
            $content[] = ['label' => 'life threatening condition', 'data' => "<p class='text-green-400'>Stable</p>"];
        } else {
            $content[] = [
                'label' => 'life threatening condition',
                'data' => collect([
                    ['label' => 'Acute coronary syndrome', 'name' => 'acute_coronary_syndrome'],
                    ['label' => 'Cardiac arrhythmia with hypotension', 'name' => 'cardiac_arrhythmia_with_hypotension'],
                    ['label' => 'Acute ischemic stroke', 'name' => 'acute_ischemic_stroke'],
                    ['label' => 'Acute ICH', 'name' => 'acute_ich'],
                    ['label' => 'Seizure', 'name' => 'seizure'],
                    ['label' => 'Cardiac arrest', 'name' => 'cardiac_arrest'],
                ])->filter(fn ($f) => $form['life_threatening_condition'][$f['name']])
                    ->values()
                    ->transform(fn ($f) => "<p class='text-red-400'>{$f['label']}</p>")
                    ->join(''),
            ];
        }

        if ($form['monitor']['standard']) {
            $content[] = ['label' => 'monitor', 'data' => '<p>Standard</p>'];
        } else {
            $data = collect([
                ['label' => 'EKG', 'name' => 'ekg'],
                ['label' => 'Observe chest pain', 'name' => 'observe_chest_pain'],
                ['label' => 'Observe neuro sign', 'name' => 'observe_neuro_sign'],
            ])->filter(fn ($f) => $form['monitor'][$f['name']])
                ->values()
                ->transform(fn ($f) => "<p>{$f['label']}</p>")
                ->join('');
            if ($form['monitor']['other']) {
                $data .= collect(explode("\n", $form['monitor']['other']))
                    ->transform(fn ($m) => "<p>$m</p>")
                    ->join('');
            }
            $content[] = ['label' => 'monitor', 'data' => $data];
        }

        return $content->all();
    }

    protected function getPrescription(array $form): array
    {
        // duration

        $content = collect([
            // SLEDD
            ['label' => 'duration', 'name' => 'duration'],

            // common
            ['label' => 'access type', 'name' => 'access_type'],
            ['label' => 'access site', 'name' => 'access_site_coagulant'],
            ['label' => 'dialyzer', 'name' => 'dialyzer'],

            // TPE
            ['label' => 'albumin concentrated (%)', 'name' => 'replacement_fluid_albumin_concentrated'],
            ['label' => 'albumin volume (ml)', 'name' => 'replacement_fluid_albumin_volume'],
            ['label' => 'ffp volume (ml)', 'name' => 'replacement_fluid_ffp_volume'],
            ['label' => 'blood pump (ml/min)', 'name' => 'blood_pump'],
            ['label' => 'filtration pump (%)', 'name' => 'filtration_pump'],
            ['label' => 'replacement pump (%)', 'name' => 'replacement_pump'],
            ['label' => 'drain pump (%)', 'name' => 'drain_pump'],
            ['label' => '10% calcium gluconate volume (ml)', 'name' => 'calcium_gluconate_10_percent_volume'],
            ['label' => '10%  calcium gluconate timing (at hour)', 'name' => 'calcium_gluconate_10_percent_timing'],

            // common
            ['label' => 'dialysate', 'name' => 'dialysate'],
            ['label' => 'dialysate flow rate (ml/min)', 'name' => 'dialysate_flow_rate'],
            ['label' => 'blood flow rate (ml/min)', 'name' => 'blood_flow_rate'],
            ['label' => 'dialysate temperature (℃)', 'name' => 'dialysate_temperature'],
            ['label' => 'bicarbonate', 'name' => 'bicarbonate'],
            ['label' => 'sodium', 'name' => 'sodium'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => ['label' => $f['label'], 'data' => $form[$f['name']]])
            ->all();

        if ($form['reverse_dialysate_flow'] ?? false) {
            if ($index = array_search('dialysate flow rate (ml/min)', array_column($content, 'label'))) {
                $content[$index]['data'] .= ' revers flow';
            }
        }

        if ($form['sodium_profile'] ?? false) {
            $content[] = [
                'label' => 'sodium profile',
                'data' => "<p>Start : {$form['sodium_profile_start']}</p><p>End : {$form['sodium_profile_end']}</p>",
            ];
        }

        if ($form['anticoagulant']) {
            $content[] = [
                'label' => 'anticoagulant',
                'data' => $this->getAnticoagulant($form),
            ];
        }

        if (isset($form['ultrafiltration_min'])) {
            $content[] = [
                'label' => 'uf (ml)',
                'data' => "{$form['ultrafiltration_min']} - {$form['ultrafiltration_max']}",
            ];
        }

        $content = array_merge($content, collect([
            ['label' => 'dry weight (kg)', 'name' => 'dry_weight'],
            ['label' => '50% glucose iv volume (ml)', 'name' => 'glucose_50_percent_iv_volume'],
            ['label' => '50% glucose iv (at hour)', 'name' => 'glucose_50_percent_iv_at'],
            ['label' => '20% albumin prime', 'name' => 'albumin_20_percent_prime'],
            ['label' => 'nutrition iv type', 'name' => 'nutrition_iv_type'],
            ['label' => 'nutrition iv volume (ml)', 'name' => 'nutrition_iv_volume'],
            ['label' => 'prc volume (unit)', 'name' => 'prc_volume'],
            ['label' => 'ffp volume (ml)', 'name' => 'ffp_volume'],
            ['label' => 'platelet volume (unit)', 'name' => 'platelet_volume'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => ['label' => $f['label'], 'data' => $form[$f['name']]])
            ->all()
        );

        if ($form['transfusion_other'] ?? false) {
            $content[] = [
                'label' => 'other transfusion',
                'data' => collect(explode("\n", $form['transfusion_other']))
                    ->transform(fn ($m) => "<p>$m</p>")
                    ->join(''),
            ];
        }

        $content = array_merge($content, collect([
            // HD+HF
            ['label' => 'hf perform at', 'name' => 'hf_perform_at'],
            ['label' => 'hf uf min', 'name' => 'hf_ultrafiltration_min'],
            ['label' => 'hf uf max', 'name' => 'hf_ultrafiltration_max'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => ['label' => $f['label'], 'data' => $form[$f['name']]])
            ->all()
        );

        // SLEDD
        if ($form['remark'] ?? false) {
            $content[] = [
                'label' => 'Note',
                'data' => collect(explode("\n", $form['remark']))
                    ->transform(fn ($m) => "<p>$m</p>")
                    ->join(''),
            ];
        }

        return $content;
    }

    protected function getAnticoagulant(array $form): string
    {
        if ($form['anticoagulant'] === 'none') {
            return collect([
                ['label' => '', 'name' => 'anticoagulant'],
                ['label' => 'drip via peripheral IV', 'name' => 'anticoagulant_none_drip_via_peripheral_iv'],
                ['label' => 'NSS 200 ml flush q hour', 'name' => 'anticoagulant_none_nss_200ml_flush_q_hour'],
            ])->filter(fn ($f) => $form[$f['name']])
                ->values()
                ->transform(fn ($f) => "<p>{$f['label']}</p>")
                ->join('');
        } elseif ($form['anticoagulant'] === 'heparin') {
            $anticoagulant = collect([
                ['label' => '', 'name' => 'anticoagulant'],
                ['label' => 'Loading (IU) : ', 'name' => 'heparin_loading_dose'],
                ['label' => 'Maintenance (IU/Hour) : ', 'name' => 'heparin_maintenance_dose'],
            ])->transform(fn ($f) => "<p>{$f['label']}{$form[$f['name']]}</p>")
                ->join('');

            return str_replace('heparin', 'Heparin', $anticoagulant);
        } elseif ($form['anticoagulant'] === 'enoxaparin') {
            $anticoagulant = collect([
                ['label' => '', 'name' => 'anticoagulant'],
                ['label' => 'Dose (ml) : ', 'name' => 'enoxaparin_dose'],
            ])->transform(fn ($f) => "<p>{$f['label']}{$form[$f['name']]}</p>")
                ->join('');

            return str_replace('enoxaparin', 'Enoxaparin', $anticoagulant);
        } elseif ($form['anticoagulant'] === 'fondaparinux') {
            $anticoagulant = collect([
                ['label' => '', 'name' => 'anticoagulant'],
                ['label' => 'Bolus dose (IU) : ', 'name' => 'fondaparinux_bolus_dose'],
            ])->transform(fn ($f) => "<p>{$f['label']}{$form[$f['name']]}</p>")
                ->join('');

            return str_replace('fondaparinux', 'Fondaparinux', $anticoagulant);
        } elseif ($form['anticoagulant'] === 'tinzaparin') {
            $anticoagulant = collect([
                ['label' => '', 'name' => 'anticoagulant'],
                ['label' => 'Dose (IU) : ', 'name' => 'tinzaparin_dose'],
            ])->transform(fn ($f) => "<p>{$f['label']}{$form[$f['name']]}</p>")
                ->join('');

            return str_replace('tinzaparin', 'Tinzaparin', $anticoagulant);
        } else {
            return $form['anticoagulant'];
        }
    }

    protected function getPatientLocation($order): ?string
    {
        $an = $order->meta['an'];
        if (collect(['scheduling', 'draft', 'submitted'])->doesntContain($order->status)) {
            if (! $an) {
                return 'ER ?';
            }

            return cache()->remember('current-admit-location-'.$an, now()->addHour(), function () use ($an) {
                $admission = (new AdmissionManager)->manage($an);
                if (! $admission['found']) {
                    return 'ER ?';
                }

                return $admission['admission']->place->name;
            });
        }

        return cache()->remember("patient-recently-location-{$order->meta['hn']}", now()->addHour(), function () use ($order) {
            $admission = (new PatientRecentlyAdmissionAction)($order->meta['hn']);

            return $admission['found']
                ? (! $admission['discharged_at'] ? $admission['ward_admit'] : 'ER ?')
                : ($admission['location'] ?? null);
        });
    }
}
