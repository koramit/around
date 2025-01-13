<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Carbon;

class TimestampUpdateAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, array $data, AvatarUser|User $user): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $message = (new CaseUpdateAction)($hashedKey, $data, $user);

        $case = $this->timestamp($hashedKey);

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

    protected function timestamp(string $hashedKey): KidneyTransplantSurvivalCaseRecord
    {
        /** @var KidneyTransplantSurvivalCaseRecord $case */
        $case = KidneyTransplantSurvivalCaseRecord::query()
            ->findByUnhashKey($hashedKey)
            ->first();

        if ($case->form['refer'] === null) {
            $case->form['date_last_update'] = now()->format('Y-m-d');
            $case->save();

            return $case;
        }

        // check latest annual cr
        $dateTx = Carbon::create($case->meta['date_transplant']);
        $yearTh = abs($dateTx->diffInYears(Carbon::now()));
        $form = $case->form;
        for($i = $yearTh; $i >= 1; $i--) {
            if (($form["year_{$i}_cr"] ?? null) && ($form["date_year_{$i}_cr"] ?? null)) {
                if ($form["date_year_{$i}_cr"] > $form['date_last_update']) {
                    $case->form['latest_cr'] = $form["year_{$i}_cr"];
                    $case->form['date_last_update'] = $form["date_year_{$i}_cr"];
                    $case->form['date_latest_cr'] = $form["date_year_{$i}_cr"];
                    $case->form['date_update_graft_status'] = $form["date_year_{$i}_cr"];
                    $case->form['date_update_patient_status'] = $form["date_year_{$i}_cr"];
                    $case->save();
                    break;
                }
            }
        }

        return $case;
    }
}
