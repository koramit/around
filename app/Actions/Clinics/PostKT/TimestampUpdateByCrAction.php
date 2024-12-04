<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;

class TimestampUpdateByCrAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, AvatarUser|User $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->first();

        if ($case->form['latest_cr'] > 4.0) {
            abort(403);
        }

        $form = $case->form;
        $form['date_update_graft_status'] = $form['date_latest_cr'];
        $form['date_update_patient_status'] = $form['date_latest_cr'];
        $form['date_last_update'] = $form['date_latest_cr'];
        $case->form = $form;
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

        return ['ok' => true];
    }
}
