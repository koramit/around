<?php

namespace App\Actions\Labs\KidneyTransplantHLATyping;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class ReportDestroyAction extends ReportAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $report = $this->getReport($hashedKey);

        if ($user->cannot('destroy', $report)) {
            abort(403, 'You are not allowed to delete this report.');
        }

        $report->update(['status' => 'deleted']);

        $report->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'delete',
        ]);

        return [
            'message' => [
                'success' => true,
                'title' => 'Report deleted successfully.',
                'message' => $report->title,
            ],
        ];
    }
}
