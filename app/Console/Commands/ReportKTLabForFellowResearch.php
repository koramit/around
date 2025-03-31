<?php

namespace App\Console\Commands;

use App\APIs\PortalAPI;
use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Reader\Exception\ReaderNotOpenedException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportKTLabForFellowResearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:kt-lab-for-fellow-research';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws IOException|UnsupportedTypeException|ReaderNotOpenedException|InvalidArgumentException|WriterNotOpenedException
     */
    public function handle(): void
    {
        $excel = new FastExcel();
        $sheet = $excel->import(storage_path('app/seeders/250314_cr_fellow_research.xlsx'));
        $data = [];
        foreach ($sheet as $row) {
            $record = [];
            $case = KidneyTransplantSurvivalCaseRecord::query()->where('meta->kt_no', $row['kt_no'])->first();
            $record['kt_no'] = $case->meta['kt_no'];
            $record['recipient_hn'] = $case->meta['hn'];
            $record['recipient_name'] = $case->meta['name'];
            $record['status'] = $case->status->label();
            $this->line($case->meta['kt_no'] . ' : ' . $case->status->label());

            // get cr
            $api = new PortalAPI;
            $result = $api->getLabFromItemCodeAllResults($case->meta['hn'], '0020');
            if (! $result['found']) {
                $this->warn($record['kt_no'] . 'lab not found');
                continue;
            }

            $labs = collect(array_map(fn ($item) => (object) $item, $result['reports']))->filter(fn ($lab) => $lab->value_numeric !== null);
            $latestLabs = $labs->sortByDesc('datetime_specimen_received');

            $record['date_latest_cr'] = explode(' ', $latestLabs->first()->datetime_specimen_received)[0];
            $record['latest_cr'] = $latestLabs->first()->value_numeric;
            $dateLastCr = Carbon::create($record['date_latest_cr']);
            $cr = $record['latest_cr'];
            $age = $case->patient->dob->diffInYears($dateLastCr);
            $record['latest_eGFR'] = $this->caleGFR($cr, $age, $case->patient->gender);

            $record['date_latest_cr_before_GL'] = null;
            $record['latest_cr_before_GL'] = null;
            $record['latest_eGFR_before_GL'] = null;

            if ($case->status !== KidneyTransplantSurvivalCaseStatus::ACTIVE) {
                foreach ($latestLabs as $lab) {
                    if ($lab->datetime_specimen_received < $case->form['date_graft_loss']) {
                        $record['date_latest_cr_before_GL'] = explode(' ', $lab->datetime_specimen_received)[0];
                        $record['latest_cr_before_GL'] = $lab->value_numeric;
                        $dateLastCr = Carbon::create($record['date_latest_cr_before_GL']);
                        $age = $case->patient->dob->diffInYears($dateLastCr);
                        $record['latest_eGFR_before_GL'] = $this->caleGFR($record['latest_cr_before_GL'], $age, $case->patient->gender);

                        break;
                    }
                }
                // $dateGraftLoss = Carbon::create($case->form['date_graft_loss']);
            }

            $data[] = $record;
        }

        $this->info((new FastExcel($data))->export(storage_path('app/seeders/250314_cr_fellow_research_filled.xlsx')));
    }

    protected function caleGFR(float $cr, int $age, string $gender): float
    {
        return $gender === 'female'
            ? ($cr <= 0.7
                ? 144 * pow(($cr / 0.7), -0.329) * pow(0.993, $age)
                : 144 * pow(($cr / 0.7), -1.209) * pow(0.993, $age))
            : ($cr <= 0.9
                ? 141 * pow(($cr / 0.9), -0.411) * pow(0.993, $age)
                : 141 * pow(($cr / 0.9), -1.209) * pow(0.993, $age));
    }
}
