<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Admission;
use App\Models\Resources\Registry;
use App\Models\User;
use App\Traits\FirstNameAware;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Collection;

class OrderExportAction extends AcuteHemodialysisAction
{
    use FirstNameAware;

    public function __invoke(string $dateNote, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $ans = AcuteHemodialysisOrderNote::query()
            ->dialysisDate($dateNote)
            ->slotOccupiedStatuses()
            ->select('meta')
            ->get()
            ->transform(function ($o) {
                if ($o->meta['an'] ?? null) {
                    return app(Hashids::class)->encode($o->meta['an']);
                } else {
                    return null;
                }
            })->filter(fn ($an) => $an)
            ->values();

        $admissions = Admission::query()
            ->withPlaceName()
            ->whereIn('an', $ans)
            ->get()
            ->transform(fn (Admission $a) => ['an' => $a->an, 'ward' => $a->place_name]);

        $orders = AcuteHemodialysisOrderNote::query()
            ->dialysisDate($dateNote)
            ->slotOccupiedStatuses()
            ->withAuthorName()
            ->withPlaceName('App\Models\Resources\Ward')
            ->with('patient')
            ->get();

        $hdALike = $orders->filter(fn ($o) => ! str_contains($o->meta['dialysis_type'], 'TPE'))->values();
        $hdALike = $hdALike->transform(fn (AcuteHemodialysisOrderNote $order) => $this->getHdRow($order, $admissions));
        $tpe = $orders->filter(fn ($o) => str_starts_with($o->meta['dialysis_type'], 'TPE'))->values();
        $tpe = $tpe->transform(fn (AcuteHemodialysisOrderNote $order) => $this->getTpeRow($order, $admissions));
        $hdTpe = $orders->filter(fn ($o) => str_starts_with($o->meta['dialysis_type'], 'HD+TPE'))->values();
        $hdTpe = $hdTpe->transform(fn (AcuteHemodialysisOrderNote $order) => $this->getHdTpeRow($order, $admissions));

        $registry = Registry::query()->find($this->REGISTRY_ID);
        $registry->actionLogs()->create([
            'action' => 'export',
            'actor_id' => $user->id,
            'payload' => [
                'report' => 'orders',
                'config' => ['date_ref' => $dateNote],
            ],
        ]);

        return ['hd_hf_sledd' => $hdALike, 'tpe' => $tpe, 'hd+tpe' => $hdTpe];
    }

    private function getHdRow(AcuteHemodialysisOrderNote $order, Collection $admissions): array
    {
        $prescription = $this->getHdHfSleddColumns($order);
        $row = array_merge($this->getCommonColumns($order, $admissions), $prescription);

        return [
            'name' => $row['name'],
            'last_name' => $row['last_name'],
            'hn' => $row['hn'],
            'an' => $row['an'],
            'ward' => $row['ward'],
            'dialysis_at' => $row['dialysis_at'],
            'dialysis_type' => $row['dialysis_type'],
            'with_HF' => $row['with_HF'],
            'with_HF_UF' => $row['with_HF_UF'],
            'duration' => $row['duration'],
            'access' => $row['access'],
            'access_site' => $row['access_site'],
            'dialyzer' => $row['dialyzer'],
            'dialysate' => $row['dialysate'],
            'BFR' => $row['BFR'],
            'DFR' => $row['DFR'],
            'temp' => $row['temp'],
            'sodium' => $row['sodium'],
            'bicarbonate' => $row['bicarbonate'],
            'anticoagulant' => $row['anticoagulant'],
            'heparin_loading' => $row['heparin_loading'],
            'heparin_maintenance' => $row['heparin_maintenance'],
            'detail' => $row['detail'],
            'UF' => $row['uf'],
            'dry_weight' => $row['dry_weight'],
            'glucose_volume' => $row['glucose_volume'],
            'glucose_hour' => $row['glucose_hour'],
            'albumin' => $row['albumin'],
            'nutrition' => $row['nutrition'],
            'nutrition_volume' => $row['nutrition_volume'],
            'LPB' => $row['prc'],
            'FFP' => $row['ffp'],
            'platelet' => $row['platelet'],
            'post_dialysis_weight' => $row['post_dialysis_weight'],
            'oxygen_support' => $row['oxygen_support'],
            'monitor' => $row['monitor'],
            'special_order' => $row['special_order'],
            'treatment_request' => $row['treatment_request'],
            'sledd_note' => $row['sledd_note'],
            'md' => $row['md'],
        ];
    }

    private function getTpeRow(AcuteHemodialysisOrderNote $order, Collection|array $admissions): array
    {
        $prescription = $this->getTpeColumns($order);
        $row = array_merge($this->getCommonColumns($order, $admissions), $prescription);

        return [
            'name' => $row['name'],
            'last_name' => $row['last_name'],
            'hn' => $row['hn'],
            'an' => $row['an'],
            'ward' => $row['ward'],
            'dialysis_at' => $row['dialysis_at'],
            'dialysis_type' => $row['dialysis_type'],
            'access' => $row['access'],
            'access_site' => $row['access_site'],
            'replacement' => $row['replacement'],
            'albumin' => $row['albumin'],
            'FFP' => $row['ffp'],
            'blood_pump' => $row['blood_pump'],
            'filtration_pump' => $row['filtration_pump'],
            'replacement_pump' => $row['replacement_pump'],
            'drain_pump' => $row['drain_pump'],
            'anticoagulant' => $row['anticoagulant'],
            'heparin_loading' => $row['heparin_loading'],
            'heparin_maintenance' => $row['heparin_maintenance'],
            'detail' => $row['detail'],
            'calcium_volume' => $row['calcium_volume'],
            'calcium_time' => $row['calcium_time'],
            'oxygen_support' => $row['oxygen_support'],
            'monitor' => $row['monitor'],
            'special_order' => $row['special_order'],
            'treatment_request' => $row['treatment_request'],
            'md' => $row['md'],
        ];
    }

    private function getHdTpeRow(AcuteHemodialysisOrderNote $order, Collection|array $admissions): array
    {
        $tpe = $this->getTpeColumns($order);
        $hd = $this->getHdHfSleddColumns($order);
        $base = $this->getCommonColumns($order, $admissions);

        return [
            'name' => $base['name'],
            'last_name' => $base['last_name'],
            'hn' => $base['hn'],
            'an' => $base['an'],
            'ward' => $base['ward'],
            'dialysis_at' => $base['dialysis_at'],
            'dialysis_type' => $base['dialysis_type'],

            'access' => $hd['access'],
            'access_site' => $hd['access_site'],
            'dialyzer' => $hd['dialyzer'],
            'dialysate' => $hd['dialysate'],
            'BFR' => $hd['BFR'],
            'DFR' => $hd['DFR'],
            'temp' => $hd['temp'],
            'sodium' => $hd['sodium'],
            'bicarbonate' => $hd['bicarbonate'],
            'anticoagulant' => $hd['anticoagulant'],
            'heparin_loading' => $hd['heparin_loading'],
            'heparin_maintenance' => $hd['heparin_maintenance'],
            'detail' => $hd['detail'],
            'UF' => $hd['uf'],
            'dry_weight' => $hd['dry_weight'],

            'glucose_volume' => $hd['glucose_volume'],
            'glucose_hour' => $hd['glucose_hour'],
            'albumin' => $hd['albumin'],
            'nutrition' => $hd['nutrition'],
            'nutrition_volume' => $hd['nutrition_volume'],
            'LPB' => $hd['prc'],
            'FFP' => $hd['ffp'],
            'platelet' => $hd['platelet'],

            'tpe_access' => $tpe['access'],
            'tpe_access_site' => $tpe['access_site'],
            'tpe_replacement' => $tpe['replacement'],
            'tpe_albumin' => $tpe['albumin'],
            'tpe_FFP' => $tpe['ffp'],
            'blood_pump' => $tpe['blood_pump'],
            'filtration_pump' => $tpe['filtration_pump'],
            'replacement_pump' => $tpe['replacement_pump'],
            'drain_pump' => $tpe['drain_pump'],
            'tpe_anticoagulant' => $tpe['anticoagulant'],
            'tpe_heparin_loading' => $tpe['heparin_loading'],
            'tpe_heparin_maintenance' => $tpe['heparin_maintenance'],
            'tpe_detail' => $tpe['detail'],
            'calcium_volume' => $tpe['calcium_volume'],
            'calcium_time' => $tpe['calcium_time'],
            'oxygen_support' => $base['oxygen_support'],
            'monitor' => $base['monitor'],
            'special_order' => $base['special_order'],
            'treatment_request' => $base['treatment_request'],
            'md' => $base['md'],
        ];
    }

    private function getCommonColumns(AcuteHemodialysisOrderNote $order, Collection $admissions): array
    {
        $form = $order->form;
        $meta = $order->meta;

        $data = [];
        $data['name'] = $order->patient->first_name;
        $data['last_name'] = $order->patient->profile['last_name'];
        $data['hn'] = $meta['hn'];
        $data['an'] = $meta['an'];
        $data['ward'] = null;
        if ($data['an']) {
            $index = $admissions->search(fn ($a) => $a['an'] == $data['an']);
            if ($index !== false) {
                $data['ward'] = $admissions[$index]['ward'];
            }
        }
        $data['dialysis_at'] = $order->place_name;
        $data['dialysis_type'] = explode(' ', $meta['dialysis_type'])[0];

        $data['post_dialysis_weight'] = $form['postdialysis_bw'] ? 'YES' : 'NO';

        $data['oxygen_support'] = $form['oxygen_support'];

        if ($form['monitor']['standard']) {
            $data['monitor'] = 'Standard';
        } else {
            $data['monitor'] = collect([
                ['label' => 'EKG', 'name' => 'ekg'],
                ['label' => 'Observe chest pain', 'name' => 'observe_chest_pain'],
                ['label' => 'Observe neuro sign', 'name' => 'observe_neuro_sign'],
            ])->filter(fn ($f) => $form['monitor'][$f['name']])
                ->values()
                ->transform(fn ($f) => $f['label'])
                ->join(', ');
            if ($form['monitor']['other']) {
                $data['monitor'] .= collect(explode("\n", $form['monitor']['other']))
                    ->join(', ');
            }
        }

        $data['special_order'] = collect([
            ['label' => 'Pre HD labs', 'name' => 'predialysis_labs_request'],
            ['label' => 'Post HD BW', 'name' => 'postdialysis_bw'],
            ['label' => 'Post HD ESA', 'name' => 'postdialysis_esa'],
            ['label' => 'Post HD Iron IV', 'name' => 'postdialysis_iron_iv'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => $f['label'])->join(', ');

        $data['treatment_request'] = $form['treatments_request'] ? collect(explode("\n", $form['treatments_request']))->join(', ') : null;
        $data['md'] = $this->getFirstName($order->author_name);

        return $data;
    }

    private function getHdHfSleddColumns(AcuteHemodialysisOrderNote $order): array
    {
        $form = $order->form;
        if (isset($form['hd'])) {
            $prescription = $form['hd'];
        } elseif (isset($form['sledd'])) {
            $prescription = $form['sledd'];
        } elseif (isset($form['hf'])) {
            $prescription = $form['hf'];
        } else {
            return [];
        }

        $data = [];

        $data['with_HF'] = $prescription['hf_perform_at'] ?? null;
        $data['with_HF_UF'] = null;
        if (isset($prescription['hf_ultrafiltration_min'])) {
            $data['with_HF_UF'] = "{$prescription['hf_ultrafiltration_min']} - {$prescription['hf_ultrafiltration_max']}";
        }
        if (isset($prescription['duration'])) {
            $data['duration'] = $prescription['duration'];
        } else {
            $data['duration'] = $this->getDuration($order->meta['dialysis_type']);
        }

        $data['access'] = $prescription['access_type'] ?? null;
        $data['access_site'] = $prescription['access_site_coagulant'] ?? null;
        $data['dialyzer'] = $prescription['dialyzer'] ?? null;
        $data['dialysate'] = $prescription['dialysate'] ?? null;
        $data['BFR'] = $prescription['blood_flow_rate'] ?? null;
        $data['DFR'] = $prescription['dialysate_flow_rate'] ?? null;
        $data['temp'] = $prescription['dialysate_temperature'] ?? null;
        $data['sodium'] = $prescription['sodium'] ?? null;
        $data['bicarbonate'] = $prescription['bicarbonate'] ?? null;

        $this->getAnticoagulant($data, $prescription);

        if (isset($prescription['ultrafiltration_min'])) {
            $data['uf'] = "{$prescription['ultrafiltration_min']} - {$prescription['ultrafiltration_max']}";
        } else {
            $data['uf'] = null;
        }

        $data['dry_weight'] = $prescription['dry_weight'] ?? null;
        $data['glucose_volume'] = $prescription['glucose_50_percent_iv_volume'] ?? null;
        $data['glucose_hour'] = $prescription['glucose_50_percent_iv_at'] ?? null;
        $data['albumin'] = $prescription['albumin_20_percent_prime'] ?? null;
        $data['nutrition'] = $prescription['nutrition_iv_type'] ?? null;
        $data['nutrition_volume'] = $prescription['nutrition_iv_volume'] ?? null;
        $data['prc'] = $prescription['prc_volume'] ?? null;
        $data['ffp'] = $prescription['ffp_volume'] ?? null;
        $data['platelet'] = $prescription['platelet_volume'] ?? null;

        $data['sledd_note'] = $form['remark'] ?? null;

        return $data;
    }

    private function getTpeColumns(AcuteHemodialysisOrderNote $order): array
    {
        $prescription = $order->form['tpe'];

        $data = [];

        $data['access'] = $prescription['access_type'] ?? null;
        $data['access_site'] = $prescription['access_site_coagulant'] ?? null;
        $data['dialyzer'] = $prescription['dialyzer'] ?? null;

        $data['replacement'] = collect([
            ['label' => 'albumin', 'name' => 'replacement_fluid_albumin'],
            ['label' => 'ffp', 'name' => 'replacement_fluid_ffp'],
        ])->filter(fn ($r) => $prescription[$r['name']])
            ->transform(fn ($r) => $r['label'])
            ->join(', ');

        if ($prescription['replacement_fluid_albumin']) {
            $data['albumin'] = "{$prescription['replacement_fluid_albumin_concentrated']}% {$prescription['replacement_fluid_albumin_volume']}ml";
        } else {
            $data['albumin'] = null;
        }

        if ($prescription['replacement_fluid_ffp']) {
            $data['ffp'] = "{$prescription['replacement_fluid_ffp_volume']}ml";
        } else {
            $data['ffp'] = null;
        }

        $data['blood_pump'] = $prescription['blood_pump'];
        $data['filtration_pump'] = $prescription['filtration_pump'];
        $data['replacement_pump'] = $prescription['replacement_pump'];
        $data['drain_pump'] = $prescription['drain_pump'];

        $this->getAnticoagulant($data, $prescription);

        $data['calcium_volume'] = $prescription['calcium_gluconate_10_percent_volume'];
        $data['calcium_time'] = $prescription['calcium_gluconate_10_percent_timing'];

        return $data;
    }

    private function getAnticoagulant(array &$data, array $prescription): void
    {
        $data['anticoagulant'] = $prescription['anticoagulant'] ?? null;
        $data['heparin_loading'] = $prescription['heparin_loading_dose'] ?? null;
        $data['heparin_maintenance'] = $prescription['heparin_maintenance_dose'] ?? null;
        if ($prescription['anticoagulant'] === 'none') {
            $data['detail'] = collect([
                ['label' => 'drip via peripheral IV', 'name' => 'anticoagulant_none_drip_via_peripheral_iv'],
                ['label' => 'NSS 200 ml flush q hour', 'name' => 'anticoagulant_none_nss_200ml_flush_q_hour'],
            ])->filter(fn ($f) => $prescription[$f['name']])
                ->values()
                ->map(fn ($f) => $f['label'])
                ->join(',');
        } elseif ($prescription['anticoagulant'] === 'enoxaparin') {
            $data['detail'] = 'Dose (ml) : '.$prescription['enoxaparin_dose'];
        } elseif ($prescription['anticoagulant'] === 'fondaparinux') {
            $data['detail'] = 'Bolus dose (IU) : '.$prescription['fondaparinux_bolus_dose'];
        } elseif ($prescription['anticoagulant'] === 'tinzaparin') {
            $data['detail'] = 'Dose (IU) : '.$prescription['tinzaparin_dose'];
        } else {
            $data['detail'] = null;
        }
    }

    private function getDuration(string $type): int
    {
        if (str_contains($type, 2)) {
            return 2;
        } elseif (str_contains($type, 3)) {
            return 3;
        } elseif (str_contains($type, 4)) {
            return 4;
        } elseif (str_contains($type, 6)) {
            return 6;
        }

        return 0;
    }
}
