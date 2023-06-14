<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Resources\Admission;
use App\Models\Resources\Ward;
use App\Models\User;
use Hashids\Hashids;
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

        $cases = AcuteHemodialysisCaseRecord::query()
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
            ->get();

        $ans = $cases->map(fn ($case) => app(Hashids::class)->encode($case->meta['an']))
            ->filter(fn ($an) => $an !== '')
            ->unique()->values();

        $admissions = Admission::query()
            ->withPlaceName()
            ->whereIn('an', $ans)
            ->get()
            ->transform(fn (Admission $a) => [
                'an' => $a->an,
                'ward' => $a->place_name,
                'encountered_at' => $a->encountered_at->format('Y-m-d'),
                'dismissed_at' => $a->dismissed_at?->format('Y-m-d'),
            ]);

        $report = $cases->transform(function ($case) use ($admissions) {
            $orders = $case->orders;

            return [
                'HN' => $case->patient->hn,
                'Name' => $case->patient->full_name,
                'AN' => $case->meta['an'],
                'Admitted on' => $admissions->where('an', $case->meta['an'])->first()['encountered_at'] ?? null,
                'Patient type' => $case->form['indications']['initiate_chronic_hd'] || $case->form['indications']['maintain_chronic_hd'] ? 'chronic' : 'acute',
                'Status' => $case->status,
                'Date first dialysis' => $orders->first()->date_note->format('Y-m-d'),
                'First MD' => $orders->first()->author_name,
                'Date last dialysis' => $orders->last()->date_note->format('Y-m-d'),
                'Last MD' => $orders->last()->author_name,
                'Total dialysis' => $orders->count(),
                'Total SLED' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'SLED'))->count(),
                'Total HD at HD unit' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && str_contains($order->place_name, 'Hemodialysis Unit'))->count(),
                'Total HD at ward' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && ! str_contains($order->place_name, 'Hemodialysis Unit') && $order->meta['covid_case'] === false)->count(),
                'Total HD covid case' => $orders->filter(fn ($order) => str_contains($order->meta['dialysis_type'], 'HD') && $order->meta['covid_case'])->count(),
                'Hemodynamic stable' => $orders->filter(fn ($order) => $order->form['hemodynamic']['stable'] === false)->count() === 0 ? 'YES' : 'NO',
                'Respiration stable' => $orders->filter(fn ($order) => $order->form['respiration']['stable'] === false)->count() === 0 ? 'YES' : 'NO',
                'Oxygen support' => $orders->filter(fn ($order) => strtolower($order->form['oxygen_support']) !== 'none')->count() === 0 ? 'YES' : 'NO',
                'Neurological stable' => $orders->filter(fn ($order) => $order->form['neurological']['stable'] === false)->count() === 0 ? 'YES' : 'NO',
                'Life threatening condition stable' => $orders->filter(fn ($order) => $order->form['life_threatening_condition']['stable'] === false)->count() === 0 ? 'YES' : 'NO',
                'Standard monitoring' => $orders->filter(fn ($order) => $order->form['monitor']['standard'] === false)->count() === 0 ? 'YES' : 'NO',
                'Renal outcome' => $case->form['renal_outcome'],
                'Patient outcome' => $case->form['patient_outcome'],
                'Last Creatine' => strtolower($case->form['patient_outcome'] ?? '') === 'dead' ? $case->form['cr_before_discharge'] : null,
                'Cause of dead' => $case->form['cause_of_dead'],
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
            ];
        });

        // return [];
        return (new FastExcel($report))->download("acute_HD-new_case-{$validated['date_start']}_to_{$validated['date_end']}.xlsx");
    }
}
