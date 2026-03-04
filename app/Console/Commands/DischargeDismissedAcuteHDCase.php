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
                $admission = Admission::findByHashKey($case->meta['an'])->first();
                if (! $admission->dismissed_at) {
                    return;
                }
                $case->actionLogs()->create([
                    'action' => 'discharge',
                    'actor_id' => 1,
                ]);
                $case->update(['status' => 'discharged']);
            });
    }
}
