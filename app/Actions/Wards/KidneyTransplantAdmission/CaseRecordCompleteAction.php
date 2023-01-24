<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class CaseRecordCompleteAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('complete', $caseRecord)) {
            abort(403, 'You are not allowed to complete this case.');
        }

        /*$caseRecord->update(['status' => 'completed']);

        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'complete',
        ]);*/

        return [
                'type' => 'success',
                'title' => 'Case completed successfully.',
                'message' => $caseRecord->title,
        ];
    }
}
