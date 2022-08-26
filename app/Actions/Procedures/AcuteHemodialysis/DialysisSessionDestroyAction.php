<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyDialysisStatusToAuthor;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class DialysisSessionDestroyAction extends AcuteHemodialysisAction
{
    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('finish', $order)) {
            abort(403);
        }

        cache()->put('no-view-log-uid-'.$user->id, true, 5);

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'finish',
        ]);

        $order->update([
            'status' => 'finished',
            'meta->finished_at' => now()->tz($this->TIMEZONE)->format('H:i'),
        ]);

        NotifyDialysisStatusToAuthor::dispatchAfterResponse($order);

        return [
            'type' => 'success',
            'title' => 'Session finished',
            'message' => '',
        ];
    }
}
