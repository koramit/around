<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderTodaySlotRequestAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        Validator::make($data, ['date_note' => 'required|date'])->validate();

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

        $note->changeRequests()->create([
            'requester_id' => $user->id,
            'changes' => ['date_note' => $this->TODAY],
            'authority_ability_id' => $this->APPROVE_ACUTE_HEMODIALYSIS_TODAY_SLOT_REQUEST_ABILITY_ID,
        ]);
        $note->update([
            'status' => 'rescheduling',
            'meta->submit_while_rescheduling' => $note->status === 'submitted',
        ]);

        return [
            'type' => 'warning',
            'title' => 'Reschedule request successful',
            'message' => 'Order pending for approval',
        ];
    }
}
