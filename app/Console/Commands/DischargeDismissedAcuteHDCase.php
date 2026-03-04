<?php

namespace App\Console\Commands;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\Resources\Admission;
use Illuminate\Console\Command;

class DischargeDismissedAcuteHDCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acute-hd:discharge-dismissed-case';

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
        //
        $status = new AcuteHemodialysisCaseRecordStatus;
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta', 'created_at', 'status'])
            ->where('status', $status->getCode('dismissed'))
            ->whereNotNull('meta->an')
            ->get()
            ->each(function (AcuteHemodialysisCaseRecord $case) {
                $this->line($case->meta['an']);
                $admission = Admission::findByHashKey($case->meta['an'])->first();
                if (! $admission->dismissed_at) {
                    return;
                }
                $log = $case->actionLogs()->create([
                    'action' => 'discharge',
                    'actor_id' => 1,
                ]);
                $log->performed_at = $admission->dismissed_at;
                $log->save();
                $case->update(['status' => 'discharged']);
                $this->line('discharged ' . $admission->dismissed_at->format('Y-m-d'));
            });
    }
}
