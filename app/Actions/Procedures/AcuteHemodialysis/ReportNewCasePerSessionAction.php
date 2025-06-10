<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\APIs\PortalAPI;
use App\Extensions\Auth\AvatarUser;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Resources\Ward;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportNewCasePerSessionAction extends AcuteHemodialysisAction
{
    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function __invoke(array $data, User|AvatarUser $user)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::validate($data, [
            'date_start' => 'required|date',
            'date_end' => 'required|date',
        ]);

        $caseIds = AcuteHemodialysisOrderNote::query()
            ->selectRaw('case_record_id, MIN(date_note) as first_order')
            ->performedStatuses()
            ->groupBy('case_record_id')
            ->having('date_note', '>=', now()->create($validated['date_start']))
            ->having('date_note', '<=', now()->create($validated['date_end']))
            ->pluck('case_record_id');

        $cases = AcuteHemodialysisCaseRecord::query()
            ->whereIn('id', $caseIds)
            ->with([
                'patient:id,profile,hn',
                'orders' => fn ($query) => $query->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note', 'form'])
                    ->withAuthorName()
                    ->withPlaceName(Ward::class)
                    ->performedStatuses()
                    ->orderBy('date_note'),
            ])->get();

        $api = new PortalAPI;
        $admissions = $cases->filter(fn ($case) => $case->meta['an'])
            ->map(fn ($case) => [...$api->getAdmissionTransfers($case->meta['an']), 'an' => $case->meta['an']]);

        $report = [];
        foreach ($cases as $case) {
            $case->orders->each(function ($order) use ($case, $admissions, &$report) {
                $dialysisType = explode(' ', $order->meta['dialysis_type']);
                $formKey = $this->getFormKey($dialysisType[0]);
                $form = $order->form;
                $dialysate = $this->splitDialysate($form[$formKey]['dialysate'] ?? null);
                $admission = $admissions->first(fn ($admission) => $admission['an'] === $case->meta['an']);
                $report[] = [
                    'HN' => $case->patient->hn,
                    'Name' => $case->patient->full_name,
                    'AN' => $case->meta['an'],
                    'patient location' => $admission
                        ? $this->getPatientLocation($admission['transfers'], $order->date_note->format('Y-m-d').' '.$order->meta['started_at'])
                        : 'แพทย์เวร/ER',
                    'covid-19 infected' => $order->meta['covid_case'] ? 'YES' : 'NO',
                    'dialysis at' => $order->place_name,
                    'dialysis type' => $dialysisType[0],
                    'patient type' => $order->meta['patient_type'],
                    'Treatments' => $order->form['treatments_request'],
                    'request predialysis lab' => $order->form['predialysis_labs_request'] ? 'YES' : 'NO',
                    'request postdialysis BW' => $order->form['postdialysis_bw'] ? 'YES' : 'NO',
                    'request postdialysis ESA' => $order->form['postdialysis_esa'] ? 'YES' : 'NO',
                    'request postdialysis iron IV' => $order->form['postdialysis_iron_iv'] ? 'YES' : 'NO',
                    'Duration' => $dialysisType[1] ?? null,
                    'Access type' => $form[$formKey]['access_type'] ?? null,
                    'Dialyzer' => $form[$formKey]['dialyzer'] ?? null,
                    'Dialysate K' => $dialysate['K'] ?? null,
                    'Dialysate Ca' => $dialysate['Ca'] ?? null,
                    'Dialysate Mg' => $dialysate['Mg'] ?? null,
                    'Dialysate flow rate' => $form[$formKey]['dialysate_flow_rate'] ?? null,
                    'Reverse flow' => ($form[$formKey]['reverse_dialysate_flow'] ?? null) !== null ? ($form[$formKey]['reverse_dialysate_flow'] ? 'YES' : 'NO') : null,
                    'Blood flow rate' => $form[$formKey]['blood_flow_rate'] ?? null,
                    'Dialysate temperature' => $form[$formKey]['dialysate_temperature'] ?? null,
                    'Bicarbonate' => $form[$formKey]['bicarbonate'] ?? null,
                    'Sodium' => $form[$formKey]['sodium'] ?? null,
                    'Anticoagulant' => $form[$formKey]['anticoagulant'] ?? null,
                    'heparin loading dose' => $form[$formKey]['heparin_loading_dose'] ?? null,
                    'heparin maintenance dose' => $form[$formKey]['heparin_maintenance_dose'] ?? null,
                    'enoxaparin dose' => $form[$formKey]['enoxaparin_dose'] ?? null,
                    'fondaparinux bolus dose' => $form[$formKey]['fondaparinux_bolus_dose'] ?? null,
                    'tinzaparin dose' => $form[$formKey]['tinzaparin_dose'] ?? null,
                    'UF min' => $form[$formKey]['ultrafiltration_min'] ?? null,
                    'UF max' => $form[$formKey]['ultrafiltration_max'] ?? null,
                    'DW' => $form[$formKey]['dry_weight'] ?? null,
                    '50% glucose volume' => $form[$formKey]['glucose_50_percent_iv_volume'] ?? null,
                    '20% albumin volume' => $form[$formKey]['albumin_20_percent_prime'] ?? null,
                    'Nutrition IV type' => $form[$formKey]['nutrition_iv_type'] ?? null,
                    'Nutrition IV volume' => $form[$formKey]['nutrition_iv_volume'] ?? null,
                    'PCR volume' => $form[$formKey]['pcr_volume'] ?? null,
                    'FFP volume' => $form[$formKey]['ffp_volume'] ?? null,
                    'Platelet volume' => $form[$formKey]['platelet_volume'] ?? null,
                    'Other transfusion' => $form[$formKey]['transfusion_other'] ?? null,
                    'Hemodynamic stable' => $form['hemodynamic']['stable'] ? 'YES' : 'NO',
                    'hypotension' => $form['hemodynamic']['hypotension'] ? 'YES' : 'NO',
                    'inotropic' => $form['hemodynamic']['inotropic_dependent'] ? 'YES' : 'NO',
                    'severe hypertension' => $form['hemodynamic']['severe_hypertension'] ? 'YES' : 'NO',
                    'Bradycardia' => $form['hemodynamic']['bradycardia'] ? 'YES' : 'NO',
                    'Arrhythmia' => $form['hemodynamic']['arrhythmia'] ? 'YES' : 'NO',
                    'Oxygen support' => $form['oxygen_support'] !== 'None' ? 'YES' : 'NO',
                    'Oxygen cannula' => $form['oxygen_support'] === 'Oxygen cannula' ? 'YES' : 'NO',
                    'Mask with bag' => $form['oxygen_support'] === 'Mask with bag' ? 'YES' : 'NO',
                    'High flow oxygen' => $form['oxygen_support'] === 'High flow oxygen' ? 'YES' : 'NO',
                    'Ventilator' => $form['oxygen_support'] === 'Ventilator' ? 'YES' : 'NO',
                    'Respiration stable' => $form['respiration']['stable'] ? 'YES' : 'NO',
                    'Hypoxia' => $form['respiration']['hypoxia'] ? 'YES' : 'NO',
                    'High risk airway obstruction' => $form['respiration']['high_risk_airway_obstruction'] ? 'YES' : 'NO',
                    'Neurological stable' => $form['neurological']['stable'] ? 'YES' : 'NO',
                    'GCS drop' => $form['neurological']['gcs_drop'] ? 'YES' : 'NO',
                    'drowsiness' => $form['neurological']['drowsiness'] ? 'YES' : 'NO',
                    'Life threatening condition stable' => $form['life_threatening_condition']['stable'] ? 'YES' : 'NO',
                    'ACS' => $form['life_threatening_condition']['acute_coronary_syndrome'] ? 'YES' : 'NO',
                    'arrhythmia with hypotension' => $form['life_threatening_condition']['cardiac_arrhythmia_with_hypotension'] ? 'YES' : 'NO',
                    'acute ischemic stroke' => $form['life_threatening_condition']['acute_ischemic_stroke'] ? 'YES' : 'NO',
                    'acute ICH' => $form['life_threatening_condition']['acute_ich'] ? 'YES' : 'NO',
                    'seizure' => $form['life_threatening_condition']['seizure'] ? 'YES' : 'NO',
                    'cardiac arrest' => $form['life_threatening_condition']['cardiac_arrest'] ? 'YES' : 'NO',
                    'Standard monitoring' => $form['monitor']['standard'] ? 'YES' : 'NO',
                    'EKG' => $form['monitor']['ekg'] ? 'YES' : 'NO',
                    'observe_chest_pain' => $form['monitor']['observe_chest_pain'] ? 'YES' : 'NO',
                    'observe_neuro_sign' => $form['monitor']['observe_neuro_sign'] ? 'YES' : 'NO',
                    'other monitoring' => $form['monitor']['other'],
                ];
            });
        }

        return (new FastExcel($report))->export(storage_path("acute_HD-new_case-per-session-{$validated['date_start']}_to_{$validated['date_end']}.xlsx"));
    }

    protected function getFormKey(string $type): ?string
    {
        return match ($type) {
            'HD', 'HD+HF', 'HD+PE' => 'hd',
            'HF' => 'hf',
            'PE' => 'pe',
            'SLEDD' => 'sledd',
            default => null,
        };
    }

    protected function splitDialysate(?string $dialysate): array
    {
        if (! $dialysate) {
            return [];
        }

        $parts = explode(' => ', $dialysate);
        $parts = explode(', ', $parts[0]);

        return [
            'K' => ($parts[0] ?? null) ? str_replace('K ', '', $parts[0]) : null,
            'Ca' => ($parts[1] ?? null) ? str_replace('Ca ', '', $parts[1]) : null,
            'Mg' => ($parts[2] ?? null) ? str_replace('Mg ', '', $parts[2]) : null,
        ];
    }

    protected function getPatientLocation(array $transfers, string $dateDialysis): string
    {
        if (count($transfers) === 1) {
            return $transfers[0]['ward_name'];
        }

        $dateDialysis = now()->create($dateDialysis, 'Asia/Bangkok')->tz('UTC')->format('Y-m-d H:i:s');
        $transfer = collect($transfers)
            ->filter(fn ($transfer) => $transfer['created_at'] <= $dateDialysis)
            ->values()
            ->sortBy('created_at')
            ->last();

        return $transfer ? $transfer['ward_name'] : 'แพทย์เวร/ER';
    }
}
