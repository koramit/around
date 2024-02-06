<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CaseRecordOffAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, ['reason' => 'required|string|max:255']);

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('off', $caseRecord)) {
            abort(403, 'You are not allowed to off this case record.');
        }

        $caseRecord->update(['status' => 'offed']);
        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'off',
            'payload' => $validated,
        ]);

        return [
            'type' => 'info',
            'title' => 'Case record has been offed successfully.',
            'message' => $caseRecord->title,
        ];
    }
}
