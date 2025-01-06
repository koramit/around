<?php

namespace App\Actions\Clinics\PostKT;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class CaseDestroyAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, AvatarUser|User $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);

        if ($user->cannot('update', $case)) {
            abort(403);
        }

        $case->status = KidneyTransplantSurvivalCaseStatus::DELETED;
        $case->save();

        $case->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'delete',
        ]);

        return [
            'type' => 'info',
            'title' => 'Case deleted successfully.',
            'message' => $case->title,
        ];
    }
}
