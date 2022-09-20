<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyDialysisStatusToAuthor;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AvatarLinkable;

class DialysisSessionStoreAction extends AcuteHemodialysisAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        /** @var AcuteHemodialysisOrderNote $order */
        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('start', $order)) {
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

        NotifyDialysisStatusToAuthor::dispatchAfterResponse($order);

        return [
            'type' => 'success',
            'title' => 'Session started',
            'message' => '',
        ];
    }
}
