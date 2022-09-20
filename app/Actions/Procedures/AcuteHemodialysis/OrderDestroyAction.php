<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyOrderCanceledToSubscribers;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;

class OrderDestroyAction extends AcuteHemodialysisAction
{
    use AvatarLinkable;

    public function __invoke(array $data, string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('destroy', $order)) {
            abort(403);
        }

        // @TODO IF status == scheduling then also expire corresponding request
        if ($order->status === 'scheduling') {
            return [
                'type' => 'warning',
                'title' => 'Cannot cancel order.',
                'message' => 'Please cancel related request first.',
            ];
        }

        $order->update(['status' => 'canceled']);

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'cancel',
            'payload' => ['reason' => $validated['reason']],
        ]);
        $this->shouldNotifyCancel($order);

        return [
            'type' => 'info',
            'title' => 'Order canceled successfully.',
            'message' => 'Order '.$order->meta['dialysis_type'].' on '.$order->date_note->format('M j').' canceled',
        ];
    }

    private function shouldNotifyCancel(AcuteHemodialysisOrderNote $order): void
    {
        // the day before @ 20:00 local time
        $ref = $order->date_note->addDays(-1)->format('Y-m-d').' '.$this->LAST_HOUR_UTC;
        $ref = now()->create($ref);
        if (now()->lessThan($ref)) {
            return;
        }

        NotifyOrderCanceledToSubscribers::dispatchAfterResponse($order);
    }
}
