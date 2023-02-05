<?php

namespace App\Actions\Wards\KidneyTransplantAdmission;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CaseRecordCancelAction extends KidneyTransplantAdmissionAction
{
    public function __invoke(array $data, string $hashedKey, User|AvatarUser $user)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $validated = Validator::validate($data, ['reason' => 'required|string|max:255']);

        $caseRecord = $this->getCaseRecord($hashedKey);

        if ($user->cannot('cancel', $caseRecord)) {
            abort(403, 'You are not allowed to cancel this case record.');
        }

        $caseRecord->update(['status' => 'canceled']);
        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'cancel',
            'payload' => $validated,
        ]);

        return [
            'type' => 'info',
            'title' => 'Case record has been canceled successfully.',
            'message' => $caseRecord->title,
        ];
    }
}
