<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderRescheduleAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['date_note' => 'required|date'])->validate();

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('reschedule', $note)) {
            abort(403);
        }

        $ensureSlotAvailable = (new SlotAvailableAction)([
                'date_note' => $validated['date_note'],
                'dialysis_type' => $note->meta['dialysis_type'],
                'dialysis_at' => $note->place_name,
            ]);
        $reply['ok'] = $ensureSlotAvailable['available'];
        if ($reply['ok']) {
            $note->update(['date_note' => $validated['date_note']]);
        }
        $reply = ['note' => $note];
        $reply['message'] = $ensureSlotAvailable['available'] ? 'ok' : 'not ok';

        return $reply;
    }
}
