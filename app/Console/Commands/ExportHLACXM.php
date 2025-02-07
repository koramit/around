<?php

namespace App\Console\Commands;

use App\Models\Notes\KidneyTransplantAdditionTissueTypingNote;
use App\Models\Notes\KidneyTransplantHLATypingNote;
use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\Registries\KidneyTransplantHLATypingCaseRecord;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\Resources\Patient;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportHLACXM extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:hla-cxm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sheet = [];
        KidneyTransplantSurvivalCaseRecord::query()
            ->with('patient')
            ->whereBetween('meta->date_transplant', ['2024-01-01', '2024-12-31'])
            ->each(function (KidneyTransplantSurvivalCaseRecord $case) use(&$sheet) {
                $this->line($case->meta['kt_no'], $case->meta['hn']);
                $r['id'] = null;
                $r['KTNO'] = $case->meta['kt_no'];
                $r['Txdate'] = $case->meta['date_transplant'];
                $r['ID'] = $case->meta['recipient_id'];
                $r['r_name'] = $case->patient->first_name;
                $r['r_surname'] = $case->patient->profile['last_name'];

                // case HLA
                $hla = KidneyTransplantHLATypingCaseRecord::query()
                    ->where('patient_id', $case->patient->id)
                    ->first();

                // recipient HLA
                $n = $hla
                    ? KidneyTransplantHLATypingNote::query()
                        ->where('case_record_id', $hla->id)
                        ->latest('date_note')
                        ->limit(1)
                        ->first()
                    : null;
                $r['RBlgr'] = null;
                $r['R_Rh'] = null;
                $r['R_HLAA1'] = null;
                $r['R_HLAA2'] = null;
                $r['R_HLAB1'] = null;
                $r['R_HLAB2'] = null;
                $r['R_HLABw1'] = null;
                $r['R_HLABw2'] = null;
                $r['R_HLADR1'] = null;
                $r['R_HLADR2'] = null;
                $r['R_HLADR52_DRB3'] = null;
                $r['R_HLADR53_DRB4'] = null;
                $r['R_HLADR51_DRB5'] = null;
                $r['R_HLADBQ1'] = null;
                $r['R_HLADBQ2'] = null;

                if ($n) {
                    $r['RBlgr'] = $n->form['abo'];
                    $r['R_Rh'] = $n->form['rh'] === 'positive' ? 'pos' : 'neg';
                    $r['R_HLAA1'] = $n->form['hla_typing_class_i_a1'];
                    $r['R_HLAA2'] = $n->form['hla_typing_class_i_a2'];
                    $r['R_HLAB1'] = $n->form['hla_typing_class_i_b1'];
                    $r['R_HLAB2'] = $n->form['hla_typing_class_i_b2'];
                    $r['R_HLABw1'] = $n->form['hla_typing_class_i_bw4'];
                    $r['R_HLABw2'] = $n->form['hla_typing_class_i_bw6'];
                    $r['R_HLADR1'] = $n->form['hla_typing_class_ii_drb11'];
                    $r['R_HLADR2'] = $n->form['hla_typing_class_ii_drb12'];
                    $r['R_HLADR52_DRB3'] = $n->form['hla_typing_class_ii_drb31'];
                    $r['R_HLADR53_DRB4'] = $n->form['hla_typing_class_ii_drb41'];
                    $r['R_HLADR51_DRB5'] = $n->form['hla_typing_class_ii_drb51'];
                    $r['R_HLADBQ1'] = $n->form['hla_typing_class_ii_dqb11'];
                    $r['R_HLADBQ2'] = $n->form['hla_typing_class_ii_dqb12'];
                }

                // donor
                $donorHn = null;
                $hlaDonor = null;
                $donor = null;
                $r['Hndonor'] = null;
                $r['donorname'] = null;
                $r['donorsurname'] = null;
                if (str_starts_with($case->meta['donor_type'], 'LD')) {
                    $donorHn = $case->form['donor_hn'];
                    $donor = Patient::query()
                        ->findByHashKey($donorHn)
                        ->first();
                }
                if ($donor) {
                    $r['Hndonor'] = $donor->hn;
                    $r['donorname'] = $donor->first_name;
                    $r['donorsurname'] = $donor->profile['last_name'];
                    $hlaDonor = KidneyTransplantHLATypingCaseRecord::query()
                        ->where('patient_id', $donor->id)
                        ->first();
                }

                // donor HLA
                // DD has no HLA
                $r['D_ABO'] = null;
                $r['D_Rh'] = null;
                $r['D_HLAA1'] = null;
                $r['D_HLAA2'] = null;
                $r['D_HLAB1'] = null;
                $r['D_HLAB2'] = null;
                $r['D_HLABw1'] = null;
                $r['D_HLABw2'] = null;
                $r['D_HLADR1'] = null;
                $r['D_HLADR2'] = null;
                $r['DHLADR52DRB3'] = null;
                $r['DHLADR53DRB4'] = null;
                $r['DHLADR51DRB5'] = null;
                $r['D_HLADBQ1'] = null;
                $r['D_HLADBQ2'] = null;

                // LD
                $n = $hlaDonor
                    ? KidneyTransplantHLATypingNote::query()
                        ->where('case_record_id', $hlaDonor->id)
                        ->latest('date_note')
                        ->limit(1)
                        ->first()
                    : null;
                if ($n) {
                    $this->line('OK');
                    $r['D_ABO'] = $n->form['abo'];
                    $r['D_Rh'] = $n->form['rh'] === 'positive' ? 'pos' : 'neg';
                    $r['D_HLAA1'] = $n->form['hla_typing_class_i_a1'];
                    $r['D_HLAA2'] = $n->form['hla_typing_class_i_a2'];
                    $r['D_HLAB1'] = $n->form['hla_typing_class_i_b1'];
                    $r['D_HLAB2'] = $n->form['hla_typing_class_i_b2'];
                    $r['D_HLABw1'] = $n->form['hla_typing_class_i_bw4'];
                    $r['D_HLABw2'] = $n->form['hla_typing_class_i_bw6'];
                    $r['D_HLADR1'] = $n->form['hla_typing_class_ii_drb11'];
                    $r['D_HLADR2'] = $n->form['hla_typing_class_ii_drb12'];
                    $r['DHLADR52DRB3'] = $n->form['hla_typing_class_ii_drb31'];
                    $r['DHLADR53DRB4'] = $n->form['hla_typing_class_ii_drb41'];
                    $r['DHLADR51DRB5'] = $n->form['hla_typing_class_ii_drb51'];
                    $r['D_HLADBQ1'] = $n->form['hla_typing_class_ii_dqb11'];
                    $r['D_HLADBQ2'] = $n->form['hla_typing_class_ii_dqb12'];
                }

                $r['R_HLADQA1'] = null;
                $r['R_HLADQA2'] = null;
                $r['R_DPA_1'] = null;
                $r['R_DPA_2'] = null;
                $r['R_DPB_1'] = null;
                $r['R_DPB_2'] = null;
                $r['R_MICA1'] = null;
                $r['R_MICA2'] = null;
                $r['D_HLADQA1'] = null;
                $r['D_HLADQA2'] = null;
                $r['D_DPA_1'] = null;
                $r['D_DPA_2'] = null;
                $r['D_DPB_1'] = null;
                $r['D_DPB_2'] = null;
                $r['D_MICA1'] = null;
                $r['D_MICA2'] = null;

                if ($hla) {
                    $n = KidneyTransplantAdditionTissueTypingNote::query()
                            ->where('case_record_id', $hla->id)
                            ->latest('date_note')
                            ->limit(1)
                            ->first();
                    if ($n) {
                        $r['R_HLADQA1'] = $n->form['tissue_typing_dqa1'];
                        $r['R_HLADQA2'] = $n->form['tissue_typing_dqa2'];
                        $r['R_DPA_1'] = $n->form['tissue_typing_dpa1'];
                        $r['R_DPA_2'] = $n->form['tissue_typing_dpa2'];
                        $r['R_DPB_1'] = $n->form['tissue_typing_dpb1'];
                        $r['R_DPB_2'] = $n->form['tissue_typing_dpb2'];
                        $r['R_MICA1'] = $n->form['tissue_typing_mica1'];
                        $r['R_MICA2'] = $n->form['tissue_typing_mica2'];
                    }
                }

                if ($hlaDonor) {
                    $n = KidneyTransplantAdditionTissueTypingNote::query()
                            ->where('case_record_id', $hlaDonor->id)
                            ->latest('date_note')
                            ->limit(1)
                            ->first();
                    if ($n) {
                        $r['D_HLADQA1'] = $n->form['tissue_typing_dqa1'];
                        $r['D_HLADQA2'] = $n->form['tissue_typing_dqa2'];
                        $r['D_DPA_1'] = $n->form['tissue_typing_dpa1'];
                        $r['D_DPA_2'] = $n->form['tissue_typing_dpa2'];
                        $r['D_DPB_1'] = $n->form['tissue_typing_dpb1'];
                        $r['D_DPB_2'] = $n->form['tissue_typing_dpb2'];
                        $r['D_MICA1'] = $n->form['tissue_typing_mica1'];
                        $r['D_MICA2'] = $n->form['tissue_typing_mica2'];
                    }
                }

                $r['R_relationship'] = null;
                $r['D_Relate_R_1Sister_2Brother_3Mother_4Father_5Daughter'] = null;
                if ($hla) {
                    $n = KidneyTransplantHLATypingReportNote::query()
                            ->where('case_record_id', $hla->id)
                            ->latest('date_note')
                            ->limit(1)
                            ->first();
                    if ($n) {
                        $r['R_relationship'] = $n->form['recipient_is'];
                        $r['D_Relate_R_1Sister_2Brother_3Mother_4Father_5Daughter'] = $n->form['donor_is'];
                    }
                }

                $sheet[] = $r;
            });

        (new FastExcel($sheet))->export(storage_path('app/export_hla_cxm.xlsx'));
    }
}
