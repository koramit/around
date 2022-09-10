<?php

namespace App\Console\Commands\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use Illuminate\Console\Command;

class DismissCase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acute-hd:dismiss-case';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto dismiss idle case';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // expire order first
        AcuteHemodialysisOrderNote::query()
            ->select(['id', 'meta', 'status', 'date_note'])
            ->whereIn('status', (new AcuteHemodialysisOrderStatus())->getActiveStatusCodes())
            ->where('date_note', '<', now()->subDays(4))
            ->get()
            ->each(function ($order) {
                $suffix = $order->date_note->longRelativeToNowDiffForHumans();
                $this->line("order expire : {$order->meta['hn']} {$order->meta['name']} {$order->status} : $suffix");
                if ($order->status === 'scheduling') {
                    $changeRequest = $order->changeRequests()->where('status', 1)->latest()->first(); // pending
                    $this->line("-> also expire request : {$changeRequest->status}");
                    $changeRequest->actionLogs()->create([
                        'action' => 'expire',
                        'actor_id' => 1
                    ]);
                    $changeRequest->update(['status' => 'expired']);
                }
                $order->actionLogs()->create([
                    'action' => 'expire',
                    'actor_id' => 1,
                ]);
                $order->update(['status' => 'expired']);
            });

        // expire case
        $status = new AcuteHemodialysisCaseRecordStatus();
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta', 'created_at', 'status'])
            ->whereIn('status', [
                $status->getCode('active'),
                $status->getCode('discharged'),
            ])
            ->whereDoesntHave('lastPerformedOrder')
            ->where(fn ($query) =>
                $query->where('created_at', '<', now()->subWeek())
                    ->orWhere('status', $status->getCode('discharged')))
            ->get()
            ->each(function ($case) {
                if ( $case->meta['an']
                    && $case->status === 'active'
                    && ($case->created_at->greaterThan(now()->subWeeks(2)))) {
                    return;
                }

                $suffix = $case->created_at->longRelativeToNowDiffForHumans();
                $this->line("case expired => {$case->meta['hn']} {$case->meta['name']} : $suffix : $case->status ");
                $this->changeCaseStatus($case, 'expire', 'expired');
            });

        // dismiss cases
        AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta', 'created_at', 'status'])
            ->where('status', $status->getCode('active'))
            ->whereHas('lastPerformedOrder', function ($query) {
                $query->where('date_note', '<', now()->subWeek()->subDay());
            })
            ->whereDoesntHave('orders', function ($query) {
                $query->whereIn('status', (new AcuteHemodialysisOrderStatus())->getActiveStatusCodes())
                    ->where('date_note', '<', now()->subDays(4));
            })
            ->with(['lastPerformedOrder'])
            ->get()
            ->each(function ($case) {
                if ($case->meta['an']
                    && $case->lastPerformedOrder->date_note->greaterThan(now()->subWeeks(2)->subDay())
                ) {
                    return;
                }

                $suffix = $case->created_at->longRelativeToNowDiffForHumans();
                $this->line("case dismissed => {$case->meta['hn']} {$case->meta['name']} : $suffix : $case->status ");
                $this->changeCaseStatus($case, 'dismiss', 'dismissed');
            });

        return 0;
    }

    protected function changeCaseStatus(AcuteHemodialysisCaseRecord $case, $action, $status)
    {
        $case->actionLogs()->create([
            'action' => $action,
            'actor_id' => 1,
        ]);
        $case->update(['status' => $status]);
    }
}
