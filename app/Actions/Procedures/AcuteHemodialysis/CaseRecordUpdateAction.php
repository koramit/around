<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\CaseRecord;

class CaseRecordUpdateAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, int $userId)
    {
        /*
         * @todo validate form before update
         */
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $caseRecord->form = $data;
        $caseRecord->updater_id = $userId;

        return $caseRecord->save();
    }
}
