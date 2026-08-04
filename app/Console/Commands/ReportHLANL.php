<?php

namespace App\Console\Commands;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportHLANL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:report-hla-nl';

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
        $source = (new FastExcel())->import(storage_path('app/dummy.xlsx'));

        $sorted = $source->sortBy('NO.');

        $hla = (new FastExcel())->import(storage_path('app/hla.xlsx'));

        $sheet = [];
        foreach ($sorted as $row) {
            $siCode = $row['Siriaj Code'];
            $siCode = str_replace('si', '', $siCode);
            $siCodes = explode(' ', $siCode);
            $siCode = $siCodes[1] ?? $siCodes[0];
            $parts = explode('-', $siCode);
            if (count($parts) === 1 || ((int) $parts[1] > 150)) {
                $this->line($row['NO.']. ' => ' . $row['Siriaj Code']);
            }
            $case = KidneyTransplantSurvivalCaseRecord::query()
                ->where('meta->kt_no',$siCode)
                ->first();

            if (!$case) {
                $this->line($row['NO.']. ' => ' . $siCode . ' => ' . ($case?->meta['recipient_id'] ?? '***************** NOT FOUND'));
            }

            $newRow = [];
            $newRow['NO.'] = $row['NO.'];
            $newRow['sex'] = $case->patient->gender;


            $hlaRow = $hla->first(fn ($r) => (int) $r['kt_id'] === (int) $case->meta['recipient_id']);

            if (!$hlaRow) {
                $this->line($row['NO.']. ' => ' . $siCode . ' => HLA not found');
            }

            $newRow['a1'] = $hlaRow['a1'] ?? null;
            $newRow['a2'] = $hlaRow['a2'] ?? null;
            $newRow['b1'] = $hlaRow['b1'] ?? null;
            $newRow['b2'] = $hlaRow['b2'] ?? null;
            $newRow['dr1'] = $hlaRow['dr1'] ?? null;
            $newRow['dr2'] = $hlaRow['dr2'] ?? null;
            $newRow['dq1'] = $hlaRow['dq1'] ?? null;
            $newRow['dq2'] = $hlaRow['dq2'] ?? null;
            $newRow['bw4'] = $hlaRow['bw4'] ?? null;
            $newRow['bw6'] = $hlaRow['bw6'] ?? null;
            $newRow['hn'] = $case->meta['hn'];
            $newRow['kt_no'] = $case->meta['kt_no'];

            $sheet[] = $newRow;
        }

        (new FastExcel($sheet))->export(storage_path('app/hla_nl.xlsx'));
    }
}
