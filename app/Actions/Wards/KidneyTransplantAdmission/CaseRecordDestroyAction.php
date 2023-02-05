<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class CaseRecordDestroyAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('destroy', $caseRecord)) {
            abort(403, 'You are not allowed to delete this case.');
        }

        $caseRecord->update(['status' => 'deleted']);

        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'delete',
        ]);

        return [
            'type' => 'info',
            'title' => 'Case deleted successfully.',
            'message' => $caseRecord->title,
        ];
    }
}
