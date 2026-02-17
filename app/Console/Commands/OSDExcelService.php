<?php

namespace App\Console\Commands;

use App\Managers\Resources\PatientManager;
use App\Models\Resources\Patient;
use DateTimeImmutable;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class OSDExcelService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:osd-excel-service {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(): void
    {
        $sheet = (new FastExcel())->import($this->argument('filePath'));

        $newSheet = [];
        $keys = array_keys($sheet[0]);
        $newKeys = [];
        foreach ($keys as $key) {
            $newKeys[] = explode("\n", $key)[0];
        }

        foreach ($sheet as $row) {
            $newRow = [];
            $hn = $row[$keys[2]];
            if (! $patient = Patient::query()->findByHashKey($hn)->first()) {
                $patient = (new PatientManager)->manage($hn)['patient'];
            }
            foreach ($keys as $index => $key) {
                if ($key === $keys[3]) {
                    $newRow[$newKeys[$index]] = $patient->profile['document_id'];
                } elseif ($key === $keys[4]) {
                    $newRow[$newKeys[$index]] = $patient->dob; //->format('Y-m-d');
                } elseif ($key === $keys[5]) {
                    if ($row[$key] instanceof DateTimeImmutable) {
                        $newRow[$newKeys[$index]] = $row[$key]; //->format('Y-m-d');
                    } else {
                        $newRow[$newKeys[$index]] = $row[$key];
                    }
                } else {
                    $newRow[$newKeys[$index]] = $row[$key];
                }
            }

            $newSheet[] = $newRow;
        }

        $outPath = str_replace('.xlsx', '_filled.xlsx', $this->argument('filePath'));
        (new FastExcel($newSheet))->export($outPath);
    }
}
