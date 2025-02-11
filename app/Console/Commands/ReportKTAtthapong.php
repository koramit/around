<?php

namespace App\Console\Commands;

use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportKTAtthapong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:kt-atthapong';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sheet = [];
        $data = (new FastExcel())->import(storage_path('app/KT_research_data_49-66.xlsx'));
        $socData = (new FastExcel())->import(storage_path('app/siriraj_012024_updated.xlsx'));
        $praData = (new FastExcel())->import(storage_path('app/SRP.xlsx'));
        $medData = (new FastExcel())->import(storage_path('app/Transplant_Med.xlsx'));
        foreach ($data as $row) {
            $case = KidneyTransplantSurvivalCaseRecord::query()
                ->where('meta->kt_no', $row['KT-no'])
                ->first();
            if (!$case) {
                $this->warn($row['KT-no'] . ' : MISSING CASE');
                $sheet[] = $row;

                continue;
            }
            $soc = $socData->where(fn ($r) => $r['KT NO'] === $row['KT-no'])->first();
            $pra = $praData->where(fn ($r) => (int) $r['ID'] === (int) $case->meta['recipient_id'])->first();
            $med = $medData->where(fn ($r) => (int) $r['ID'] === (int) $case->meta['recipient_id'])->first();

            if (! ($soc && $pra && $med)) {
                $this->warn($row['KT-no'] . ' : MISSING RECORDS ' . (!$soc ? 'soc' : '') . (!$pra ? 'pra' : '') . (!$med ? 'med' : ''));
            }

            $tuple = [];
            // KT-no
            $tuple['KT-no'] = $row['KT-no'];
            // RecipientHN
            $tuple['RecipientHN'] = $row['RecipientHN'];
            // Recipient name
            $tuple['Recipient name'] = $row['Recipient name'];
            // Recipient surname
            $tuple['Recipient surname'] = $row['Recipient surname'];
            // Txdate
            $tuple['Txdate'] = $row['Txdate'];
            // Donortype
            $tuple['Donortype'] = $row['Donortype'];

            // DOB => SS
            $tuple['DOB'] = $case->patient->dob; //->format('Y-m-d');
            // Age at Tx => SS
            $tuple['Age at Tx'] = (int) abs($case->patient->dob->diffInYears($row['Txdate']));
            // Donor age => SOC.Donor_Age
            $tuple['Donor age'] = $soc['Donor_Age'] ?? null;
            // Pre PRA I => SRP
            $tuple['Pre PRA I'] = $pra['LastPRA1'];
            $tuple['DateLastPRA1'] = $pra['DateLastPRA1'];
            // Pre PRA II => SRP
            $tuple['Pre PRA II'] = $pra['LastPRA2'];
            $tuple['DateLastPRA2'] = $pra['DateLastPRA2'];
            // Peak PRA I => SRP
            $tuple['Peak PRA I'] = $pra['MaxPRA1'];
            // Peak PRA II => SRP
            $tuple['Peak PRA II'] = $pra['MaxPRA2'];
            // HLA-A mm => SOC.Recipient_Missmatch_A
            $tuple['HLA-A mm'] = $soc['Recipient_Missmatch_A'] ?? null;
            // HLA-B mm => SOC.Recipient_Missmatch_B
            $tuple['HLA-B mm'] = $soc['Recipient_Missmatch_B'] ?? null;
            // HLA-DR mm => SOC.Recipient_Missmatch_DR
            $tuple['HLA-DR mm'] = $soc['Recipient_Missmatch_DR'] ?? null;
            // induction => Transplant_Med
            $tuple['induction'] = $med['Ab Induction'];
            // IL-2 Simulect (basiliximab) and Zenapax (daclizumab) => Transplant_Med
            $tuple['IL-2 Simulect (basiliximab) and Zenapax (daclizumab)'] = $med['InductionIL2mAb'];
            // ATG => Transplant_Med
            $tuple['ATG'] = $med['InductionATG_ALT'];
            // Cr 1 y => SS
            $tuple['Cr 1 y'] = $case->form['year_1_cr'] ?? null;
            // Cr 2 y => SS
            $tuple['Cr 2 y'] = $case->form['year_2_cr'] ?? null;
            // Cr 3 y => SS
            $tuple['Cr 3 y'] = $case->form['year_3_cr'] ?? null;
            // Cr 4 y => SS
            $tuple['Cr 4 y'] = $case->form['year_4_cr'] ?? null;
            // Cr 5 y => SS
            $tuple['Cr 5 y'] = $case->form['year_5_cr'] ?? null;
            // Cr 10 y => SS
            $tuple['Cr 10 y'] = $case->form['year_10_cr'] ?? null;
            // Cr ล่าสุด => SS
            $tuple['Cr ล่าสุด'] = $case->form['latest_cr'] ?? null;
            // date Cr ล่าสุด => SS
            $tuple['date Cr ล่าสุด'] = $case->form['date_latest_cr']
                ? Carbon::create($case->form['date_latest_cr'])
                : null;
            // Graft status (function/loss) => SS
            $tuple['Graft status'] = $case->form['graft_status'];
            // date graft loss => SS
            $tuple['date graft loss'] = $case->form['date_graft_loss']
                ? Carbon::create($case->form['date_graft_loss'])
                : null;
            // Cause of graft loss => SS
            $tuple['Cause of graft loss'] = $case->form['graft_loss_codes'][0]['code'] ?? null;
            // Patient status (alive or death) => SS
            $tuple['Patient status (alive or death)'] = $case->form['patient_status'];
            // date dead => SS
            $tuple['date dead'] = $case->form['date_dead']
                ? Carbon::create($case->form['date_dead'])
                : null;
            // cause of death => SS
            $tuple['cause of death'] = $case->form['dead_report_codes'][0]['code'] ?? null;

            $sheet[] = $tuple;
        }

        (new FastExcel($sheet))->export(storage_path('app/KT_research_data_49-66_filled.xlsx'));
    }
}
