<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use Illuminate\Support\Carbon;

class AnnualUpdateAction extends CaseBaseAction
{
    public function __invoke(string $hashedKey, User|AvatarUser $user, bool $useLatestCr = false): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $case = $this->getCaseRecord($hashedKey);

        if (in_array($case->form['graft_status'], ['graft loss', 'loss follow up'])) {
            return ['ok' => true, 'graft_function' => false];
        }

        $this->updateCreatinine($case);

        $dateTx = Carbon::create($case->meta['date_transplant']);
        $yearTh = now()->year - $dateTx->year;

        if ($useLatestCr) {
            $case->form["year_{$yearTh}_cr"] = $case->form['latest_cr'];
            $case->form["date_year_{$yearTh}_cr"] = $case->form['date_latest_cr'];
        }

        if (! isset($case->form["year_{$yearTh}_cr"])) {
            return ['ok' => true, 'graft_function' => false];
        }

        $annualCr = (float) $case->form["year_{$yearTh}_cr"];

        if ($annualCr > 4.0) {
            return ['ok' => true, 'graft_function' => false];
        }

        $case->form['graft_status'] = 'graft function';
        $case->form['date_update_graft_status'] = $case->form['date_latest_cr'];
        $case->form['patient_status'] = 'alive';
        $case->form['date_update_patient_status'] = $case->form['date_latest_cr'];
        $case->form['date_last_update'] = $case->form['date_latest_cr'];
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

        return ['ok' => true, 'graft_function' => true];
    }
}
