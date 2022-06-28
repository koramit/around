<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\CaseRecord;
use App\Models\User;

class CaseRecordUpdateAction extends AcuteHemodialysisAction
{
    /**
     * @todo authorize
     * @todo validate form before update
     */
    public function __invoke(array $data, string $hashedKey, User $user)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $caseRecord->form = $data;
        $caseRecord->updater_id = $user->id;

        return $caseRecord->save();
    }
}
