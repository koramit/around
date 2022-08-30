<?php

namespace App\Console\Commands\Procedures\AcuteHemodialysis;

use App\Managers\Resources\AdmissionManager;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use Illuminate\Console\Command;

class AssignAdmission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acute-hd:assign-an';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto assign admission to case';

    public function handle(): int
    {
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta'])
            ->with('lastOrder')
            ->where('created_at', '<', now()->subHour())
            ->whereNull('meta->an')
            ->get()
            ->each(function (AcuteHemodialysisCaseRecord $c) {
                $admission = (new AdmissionManager())->manage($c->meta['hn'], true);
                if (! $admission['found']) {
                    $this->line("{$c->meta['name']} no admission found");

                    return;
                }

                $admission = $admission['admission'];
                if (
                    (! $admission->dismissed_at)
                    || ($c->lastOrder && $c->lastOrder->date_note->lessThan($admission->dismissed_at))
                ) {
                    $c->meta['an'] = $admission->an;
                    $dc = 'without discharge';
                    if ($admission->dismissed_at) {
                        $c->status = 'discharged';
                        $c->actionLogs()->create([
                            'action' => 'discharge',
                            'actor_id' => 1,
                        ]);
                        $dc = 'discharged';
                    }
                    $c->save();
                    $this->line("{$c->meta['name']} admission assigned, $dc");

                    return;
                }

                $this->line("{$c->meta['name']} admission not fit");
            });

        return 0;
    }
}
