<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderRescheduleAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['date_note' => 'required|date'])->validate();

        $order = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('reschedule', $order)) {
            abort(403);
        }

        if ($order->date_note->format('Y-m-d') === $validated['date_note']) {
            return [
                'type' => 'danger',
                'title' => 'Cannot reschedule with same date.',
                'message' => 'Please pay 500 BAHT fee first.',
            ];
        }

        $ensureSlotAvailable = (new SlotAvailableAction)([
            'date_note' => $validated['date_note'],
            'dialysis_type' => $order->meta['dialysis_type'],
            'dialysis_at' => $order->place_name,
        ]);

        if (! $ensureSlotAvailable['available']) {
            return [
                'type' => 'danger',
                'title' => 'Cannot reschedule.',
                'message' => 'Slot not available on '.now()->create($validated['date_note'])->format('M j').'.',
            ];
        }

        // check if reschedule out from today then make a request
        if ($order->date_note->format('Y-m-d') === $this->TODAY) {
            /** @var AcuteHemodialysisSlotRequest $request */
            $request = $order->changeRequests()->create([
                'requester_id' => $user->id,
                'changes' => ['date_note' => $validated['date_note']],
                'authority_ability_id' => $this->APPROVE_ACUTE_HEMODIALYSIS_TODAY_SLOT_REQUEST_ABILITY_ID,
            ]);
            $request->actionLogs()->create([
                'action' => 'create',
                'actor_id' => $user->id,
            ]);
            $order->actionLogs()->create([
                'action' => 'request_change',
                'actor_id' => $user->id,
                'payload' => ['request_id' => $request->id],
            ]);
            $order->update([
                'status' => 'scheduling',
                'meta->submit_while_rescheduling' => $order->status === 'submitted',
            ]);

            return [
                'type' => 'warning',
                'title' => 'Request pending for approval.',
                'message' => 'Form '.$order->date_note->format('M j').' to '.now()->create($validated['date_note'])->format('M j').'.',
            ];
        }

        $message = [
            'type' => 'info',
            'title' => 'Reschedule has been successful.',
            'message' => 'Form '.$order->date_note->format('M j').' to '.now()->create($validated['date_note'])->format('M j').'.',
        ];
        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'reschedule',
            'payload' => ['from' => $order->date_note->format('Y-m-d'), 'to' => $validated['date_note']],
        ]);
        $order->update(['date_note' => $validated['date_note']]);

        return $message;
    }
}
