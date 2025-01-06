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

class ReportNewCaseRecordAction extends AcuteHemodialysisAction
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

        /*$cases = AcuteHemodialysisCaseRecord::query()
            ->whereHas('firstPerformedOrder', fn ($query) => $query->whereBetween('date_note', [
                now()->create($validated['date_start']),
                now()->create($validated['date_end']),
            ]))
            ->with([
                'patient:id,profile,hn',
                'orders' => fn ($query) => $query->select(['id', 'case_record_id', 'author_id', 'status', 'meta', 'date_note', 'form'])
                    ->withAuthorName()
                    ->withPlaceName(Ward::class)
                    ->performedStatuses()
                    ->orderBy('date_note'),
            ])
            ->get();*/

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

        $report = $cases->transform(function ($case) use ($admissions) {
            $orders = $case->orders;
            $admission = $admissions->where('an', $case->meta['an'])->first();

            return [
                'HN' => $case->patient->hn,
                'Name' => $case->patient->full_name,
                'AN' => $case->meta['an'],
                'Admitted on' => $admission
                    ? $admission['admitted_at']
                    : null,
                'Ward admit' => $admission
                    ? $admission['transfers'][0]['ward_name']
                    : null,
                'Patient type' => $case->form['indications']['initiate_chronic_hd'] || $case->form['indications']['maintain_chronic_hd'] ? 'chronic' : 'acute',
                'Status' => $case->status,
                'Date first dialysis' => $orders->first()->date_note->format('Y-m-d'),
                'First MD' => $orders->first()->author_name,
                'Date last dialysis' => $orders->last()->date_note->format('Y-m-d'),
                'Last MD' => $orders->last()->author_name,
                'Discharged on' => $admission
                    ? $admission['discharged_at']
                    : null,
                'ward discharge' => $admission
                    ? $admission['transfers'][$admission['transfer_count'] - 1]['ward_name']
                    : null,
                'Renal diagnosis' => $case->form['renal_diagnosis'],
                'Admission diagnosis' => $case->form['admission_diagnosis'],
                'DM' => $case->form['comorbidities']['dm'] ? 'YES' : 'NO',
                'HT' => $case->form['comorbidities']['ht'] ? 'YES' : 'NO',
                'DLP' => $case->form['comorbidities']['dlp'] ? 'YES' : 'NO',
                'CAD' => $case->form['comorbidities']['coronary_artery_disease'] ? 'YES' : 'NO',
                'CVD' => $case->form['comorbidities']['cerebrovascular_disease'] ? 'YES' : 'NO',
                'COPD' => $case->form['comorbidities']['copd'] ? 'YES' : 'NO',
                'Cirrhosis' => $case->form['comorbidities']['cirrhosis'] ? 'YES' : 'NO',
                'Cancer' => $case->form['comorbidities']['cancer'] ? 'YES' : 'NO',
                'Other comorbidity' => $case->form['comorbidities']['other'],
                'Volume overload' => $case->form['indications']['volume_overload'] ? 'YES' : 'NO',
                'Metabolic acidosis' => $case->form['indications']['metabolic_acidosis'] ? 'YES' : 'NO',
                'Hyperkalemia' => $case->form['indications']['hyperkalemia'] ? 'YES' : 'NO',
                'Toxin removal' => $case->form['indications']['toxin_removal'] ? 'YES' : 'NO',
                'Initiate chronic HD' => $case->form['indications']['initiate_chronic_hd'] ? 'YES' : 'NO',
                'Maintain chronic HD' => $case->form['indications']['maintain_chronic_hd'] ? 'YES' : 'NO',
                'Change from PD' => $case->form['indications']['change_from_pd'] ? 'YES' : 'NO',
                'Uremia' => $case->form['indications']['uremia'] ? 'YES' : 'NO',
                'DGF' => $case->form['indications']['delayed_graft_function'] ? 'YES' : 'NO',
                'Other indication' => $case->form['indications']['other'],
                'HBsAg' => $case->form['hbs_ag'],
                'Anti HCV' => $case->form['anti_hcv'],
                'Anti HIV' => $case->form['anti_hiv'],
                'Medical scheme' => $case->form['insurance'],
                'Renal outcome' => $case->form['renal_outcome'],
                'Patient outcome' => $case->form['patient_outcome'],
                'Last Creatine' => strtolower($case->form['patient_outcome'] ?? '') === 'dead' ? $case->form['cr_before_discharge'] : null,
                'Cause of dead' => $case->form['cause_of_dead'],
                'Total dialysis(ไม่นับ TPE)' => $orders->count() - $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'TPE'))->count(),
                'Total SLED' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'SLED'))->count(),
                'Total HD at HD unit' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && str_contains($order->place_name, 'Hemodialysis Unit'))->count(),
                'Total HD at ward' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && ! str_contains($order->place_name, 'Hemodialysis Unit') && $order->meta['covid_case'] === false)->count(),
                'Total HD covid case' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && $order->meta['covid_case'])->count(),
                'Total TPE' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'TPE'))->count(),
                'Session count' => $orders->count(),
                'Hemodynamic stable count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['stable'] === true)->count(),
                'hypotension count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['hypotension'] === true)->count(),
                'inotropic dependent count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['inotropic_dependent'] === true)->count(),
                'severe hypertension count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['severe_hypertension'] === true)->count(),
                'bradycardia count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['bradycardia'] === true)->count(),
                'arrhythmia count' => $orders->filter(fn ($order) => $order->form['hemodynamic']['arrhythmia'] === true)->count(),

                'Respiration stable count' => $orders->filter(fn ($order) => $order->form['respiration']['stable'] === true)->count(),
                'hypoxia count' => $orders->filter(fn ($order) => $order->form['respiration']['hypoxia'] === true)->count(),
                'high risk airway obstruction count' => $orders->filter(fn ($order) => $order->form['respiration']['high_risk_airway_obstruction'] === true)->count(),

                'Oxygen support count' => $orders->filter(fn ($order) => strtolower($order->form['oxygen_support']) !== 'none')->count(),

                'Neurological stable count' => $orders->filter(fn ($order) => $order->form['neurological']['stable'] === true)->count(),
                'gcs drop count' => $orders->filter(fn ($order) => $order->form['neurological']['gcs_drop'] === true)->count(),
                'drowsiness count' => $orders->filter(fn ($order) => $order->form['neurological']['drowsiness'] === true)->count(),

                'Life threatening condition stable count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['stable'] === true)->count(),
                'acute coronary syndrome count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['acute_coronary_syndrome'] === true)->count(),
                'cardiac arrhythmia with hypotension count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['cardiac_arrhythmia_with_hypotension'] === true)->count(),
                'acute ischemic stroke count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['acute_ischemic_stroke'] === true)->count(),
                'acute ich count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['acute_ich'] === true)->count(),
                'seizure count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['seizure'] === true)->count(),
                'cardiac arrest count' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['cardiac_arrest'] === true)->count(),

                'Standard monitoring count' => $orders->filter(fn ($order) => $order->form['monitor']['standard'] === true)->count(),
                'ekg count' => $orders->filter(fn ($order) => $order->form['monitor']['ekg'] === true)->count(),
                'observe chest pain count' => $orders->filter(fn ($order) => $order->form['monitor']['observe_chest_pain'] === true)->count(),
                'observe neuro sign count' => $orders->filter(fn ($order) => $order->form['monitor']['observe_neuro_sign'] === true)->count(),
                'monitoring other' => $orders->filter(fn ($order) => $order->form['monitor']['other'])->count(),
            ];
        });

        return (new FastExcel($report))->export(storage_path("acute_HD-new_case-{$validated['date_start']}_to_{$validated['date_end']}.xlsx"));
    }
}
