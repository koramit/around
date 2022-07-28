<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord as CaseRecord;
use App\Models\User;

class CaseRecordUpdateAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user)
    {
        // @TODO validate form before update

        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $caseRecord = CaseRecord::query()->findByUnhashKey($hashedKey)->firstOrFail();

        $caseRecord->form = $data;

        return $caseRecord->save();
    }
}
