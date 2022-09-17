<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Jobs\Procedures\AcuteHemodialysis\NotifyDialysisStatusToAuthor;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AvatarLinkable;

class DialysisSessionDestroyAction extends AcuteHemodialysisAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
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
