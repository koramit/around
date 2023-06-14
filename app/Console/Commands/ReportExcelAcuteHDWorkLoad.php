<?php

namespace App\Console\Commands;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportExcelAcuteHDWorkLoad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-excel:acute-hd-work-load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @TODO dialysis_at_chronic_unit lost in somepoint
     * @TODO remove extra slot after reschedule if needed
     */
    public function handle()
    {
        $data = AcuteHemodialysisOrderNote::query()
            ->whereIn('status', (new AcuteHemodialysisOrderStatus())->getPerformedStatusCodes())
            ->orderBy('date_note')
            ->get()
            ->transform(fn ($o) => [
                'date' => $o->date_note->format('d M Y'),
                'week_day' => $o->date_note->format('l'),
                'patient' => $o->meta['patient_type'],
                'type' => explode(' ', $o->meta['dialysis_type'])[0],
                'hours' => $this->getDuration($o->meta['dialysis_type']) ?? $o->form['sledd']['duration'],
                'ventilator' => $o->form['oxygen_support'] === 'Ventilator' ? 1 : 0,
                'extra' => $o->meta['extra_slot'] ? 1 : 0,
                'covid' => $o->meta['covid_case'] ? 1 : 0,
                'unit' => ($o->meta['dialysis_at_chronic_unit'] ?? false)
                    ? 'C'
                    : ($o->place_id === 72
                        ? 'A'
                        : 'ICU'
                    ),
            ]);

        (new FastExcel($data))->export(storage_path('app/excel/slots.xlsx'));

        return 0;
    }

    private function getDuration(string $type): ?int
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

        return null;
    }
}
