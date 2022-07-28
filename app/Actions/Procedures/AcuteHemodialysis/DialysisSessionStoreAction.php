<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class DialysisSessionStoreAction extends AcuteHemodialysisAction
{
    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('perform', $order)) {
            abort(403);
        }

        cache()->put('no-view-log-uid-'.$user->id, true, 5);

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'start',
        ]);

        $order->update([
            'status' => 'started',
            'meta->started_at' => now()->tz($this->TIMEZONE)->format('H:i'),
        ]);

        // @TODO notify author

        return [
            'type' => 'success',
            'title' => 'Session started',
            'message' => '',
        ];
    }
}
