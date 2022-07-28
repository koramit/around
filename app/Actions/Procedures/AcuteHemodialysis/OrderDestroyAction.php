<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderDestroyAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        // @TODO IF status == scheduling then also expire corresponding request

        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('destroy', $order)) {
            abort(403);
        }

        // IF status == scheduling then also expire corresponding request
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

        return [
            'type' => 'info',
            'title' => 'Order canceled successfully.',
            'message' => 'Order '.$order->meta['dialysis_type'].' on '.$order->date_note->format('M j').' canceled',
        ];
    }
}
