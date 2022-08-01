<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Resources\Admission;
use App\Models\User;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Collection;

class OrderExportAction
{
    public function __invoke(?string $dateNote, User $user): array
    {
        // $data = (new App\Actions\Procedures\AcuteHemodialysis\OrderExportAction)('2022-08-01', User::first())
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        if (config('database.default') === 'sqlite') {
            $dateNote = $dateNote.' 00:00:00';
        }

        $ans = AcuteHemodialysisOrderNote::query()
            ->where('date_note', $dateNote)
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
            ->with('patient')
            ->slotOccupiedStatuses()
            ->withPlaceName('App\Models\Resources\Ward')
            ->withAuthorName()
            ->where('date_note', $dateNote)
            ->get()
            ->transform(fn (AcuteHemodialysisOrderNote $order) => $this->getHdHfSleddRow($order, $admissions));

        return [$admissions, $orders];
    }

    private function getHdHfSleddRow(AcuteHemodialysisOrderNote $order, Collection $admissions): array
    {
        $form = $order->form;
        if (isset($form['hd'])) {
            $form = $form['hd'];
        } elseif (isset($form['sledd'])) {
            $form = $form['sledd'];
        } elseif (isset($form['hf'])) {
            $form = $form['hf'];
        }

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
        $data['dialysis_type'] = $meta['dialysis_type'];

        $data['with_HF'] = $form['hf_perform_at'] ?? null;
        $data['with_HF_UF'] = null;
        if (isset($form['hf_ultrafiltration_min'])) {
            $data['with_HF_UF'] = "{$form['hf_ultrafiltration_min']} - {$form['hf_ultrafiltration_max']}";
        }
        $data['sledd_duration'] = $form['duration'] ?? null;

        $data['access'] = $form['access_type'] ?? null;
        $data['access_site'] = $form['access_site_coagulant'] ?? null;
        $data['dialyzer'] = $form['dialyzer'] ?? null;
        $data['dialysate'] = $form['dialysate'] ?? null;
        $data['BFR'] = $form['blood_flow_rate'] ?? null;
        $data['DFR'] = $form['dialysate_flow_rate'] ?? null;
        $data['temp'] = $form['dialysate_temperature'] ?? null;
        $data['sodium'] = $form['sodium'] ?? null;
        $data['bicarbonate'] = $form['bicarbonate'] ?? null;

        $data['anticoagulant'] = $form['anticoagulant'] ?? null;
        $data['heparin_loading'] = $form['heparin_loading_dose'] ?? null;
        $data['heparin_maintenance'] = $form['heparin_maintenance_dose'] ?? null;
        if ($form['anticoagulant'] === 'none') {
            $data['detail'] = collect([
                ['label' => 'drip via peripheral IV', 'name' => 'anticoagulant_none_drip_via_peripheral_iv'],
                ['label' => 'NSS 200 ml flush q hour', 'name' => 'anticoagulant_none_nss_200ml_flush_q_hour'],
            ])->filter(fn ($f) => $form[$f['name']])
                ->values()
                ->map(fn ($f) => $f['label'])
                ->join(',');
        } elseif ($form['anticoagulant'] === 'enoxaparin') {
            $data['detail'] = 'Dose (ml) : '.$form['enoxaparin_dose'];
        } elseif ($form['anticoagulant'] === 'fondaparinux') {
            $data['detail'] = 'Bolus dose (IU) : '.$form['fondaparinux_bolus_dose'];
        } elseif ($form['anticoagulant'] === 'tinzaparin') {
            $data['detail'] = 'Dose (IU) : '.$form['tinzaparin_dose'];
        } else {
            $data['detail'] = null;
        }

        if (isset($form['ultrafiltration_min'])) {
            $data['uf'] = "{$form['ultrafiltration_min']} - {$form['ultrafiltration_max']}";
        } else {
            $data['uf'] = null;
        }

        $data['dry_weight'] = $form['dry_weight'] ?? null;
        $data['glucose_volume'] = $form['glucose_50_percent_iv_volume'] ?? null;
        $data['glucose_hour'] = $form['glucose_50_percent_iv_at'] ?? null;
        $data['albumin'] = $form['albumin_20_percent_prime'] ?? null;
        $data['nutrition'] = $form['nutrition_iv_type'] ?? null;
        $data['nutrition_volume'] = $form['nutrition_iv_volume'] ?? null;
        $data['prc'] = $form['prc_volume'] ?? null;
        $data['ffp'] = $form['ffp_volume'] ?? null;
        $data['platelet'] = $form['platelet_volume'] ?? null;

        $data['post_dialysis_weight'] = $form['postdialysis_bw'] ? 'YES' : 'NO';

        $data['oxygen_support'] = $form['oxygen_support'];

        if ($form['monitor']['standard']) {
            $data['oxygen_support'] = 'Standard';
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
            ['label' => 'Predialysis labs', 'name' => 'predialysis_labs_request'],
            ['label' => 'Postdialysis BW', 'name' => 'postdialysis_bw'],
            ['label' => 'Postdialysis ESA', 'name' => 'postdialysis_esa'],
            ['label' => 'Postdialysis Iron IV', 'name' => 'postdialysis_iron_iv'],
        ])->filter(fn ($f) => $form[$f['name']] ?? null)
            ->values()
            ->transform(fn ($f) => $f['label'])->join(', ');

        $data['treatment_request'] = $form['treatments_request'] ? collect(explode("\n", $form['treatments_request']))->join(', ') : null;
        $data['sledd_note'] = $form['remark'] ?? null;

        return $data;
    }
}
