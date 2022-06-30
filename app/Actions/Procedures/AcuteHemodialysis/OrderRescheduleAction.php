<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class OrderRescheduleAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('reschedule', $note)) {
            abort(403);
        }

        $ensureSlotAvailable = (new SlotAvailableAction)($data);
        $reply['ok'] = $ensureSlotAvailable['available'];
        if ($reply['ok']) {
            $note->update(['date_note' => $data['date_note']]);
        }
        $reply = ['note' => $note];
        $reply['message'] = $ensureSlotAvailable['available'] ? 'ok' : 'not ok';

        return $reply;
    }
}
