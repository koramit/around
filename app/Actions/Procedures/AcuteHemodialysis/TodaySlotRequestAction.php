<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class TodaySlotRequestAction extends AcuteHemodialysisAction
{
    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('reschedule', $note)) {
            abort(403);
        }

        $ensureSlotAvailable = (new SlotAvailableAction)([
            'date_note' => $this->TODAY,
            'dialysis_type' => $note->meta['dialysis_type'],
            'dialysis_at' => $note->place_name,
        ]);
        if (! $ensureSlotAvailable['available']) {
            return [
                'type' => 'danger',
                'title' => 'Cannot reschedule',
                'message' => 'No slot available',
            ];
        }

        /** @var AcuteHemodialysisSlotRequest $request */
        $request = $note->changeRequests()->create([
            'requester_id' => $user->id,
            'changes' => ['date_note' => $this->TODAY],
            'authority_ability_id' => $this->APPROVE_ACUTE_HEMODIALYSIS_TODAY_SLOT_REQUEST_ABILITY_ID,
        ]);
        $request->actionLogs()->create([
            'action' => 'create',
            'actor_id' => $user->id,
        ]);
        $note->actionLogs()->create([
            'action' => 'request_change',
            'actor_id' => $user->id,
            'payload' => ['request_id' => $request->id],
        ]);
        $note->update([
            'status' => 'scheduling',
            'meta->submit_while_rescheduling' => $note->status === 'submitted',
        ]);

        return [
            'type' => 'warning',
            'title' => 'Reschedule request successful.',
            'message' => 'Order pending for approval.',
        ];
    }
}
