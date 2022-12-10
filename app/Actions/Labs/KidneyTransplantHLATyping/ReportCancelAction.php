<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ReportCancelAction extends ReportAction
{
    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        $report = $this->getReport($hashedKey);

        if ($user->cannot('cancel', $report)) {
            abort(403, 'You are not allowed to cancel this report.');
        }

        $report->update(['status' => 'canceled']);

        $report->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'cancel',
            'payload' => ['reason' => $validated['reason']],
        ]);

        return [
            'type' => 'info',
            'title' => 'Report has been canceled successfully.',
            'message' => $report->title,
        ];
    }
}
