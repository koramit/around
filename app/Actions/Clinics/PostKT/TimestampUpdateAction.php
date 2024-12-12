<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;

class TimestampUpdateAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, array $data, AvatarUser|User $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $message = (new CaseUpdateAction)($hashedKey, $data, $user);

        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->first();

        $case->form['date_last_update'] = now()->format('Y-m-d');
        $case->save();

        $snapshot = $case->form;
        $snapshot['status'] = $case->status->label();

        $case->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'update',
            'payload' => [
                'snapshot' => $snapshot,
            ],
        ]);

        return $message;
    }
}
