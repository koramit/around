<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;

class ShowCase extends CaseBaseAction
{
    public function __invoke(string $hashedKey, AvatarUser|User $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);

        return [
            'donor_name' => $case->form['donor_name'],
            'case_no' => $case->case_no,
            'donor_gender' => $case->form['donor_gender'],
        ];
    }
}
