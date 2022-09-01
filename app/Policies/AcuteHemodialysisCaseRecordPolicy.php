<?php

namespace App\Policies;

use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcuteHemodialysisCaseRecordPolicy
{
    use HandlesAuthorization;

    public function update(User $user, AcuteHemodialysisCaseRecord $caseRecord): bool
    {
        return $user->can('edit_acute_hemodialysis_case')
            && collect(['active', 'discharged', 'dismissed'])->contains($caseRecord->status);
    }

    public function destroy(User $user, AcuteHemodialysisCaseRecord $caseRecord): bool
    {
        return $user->can('edit_acute_hemodialysis_case')
            && $caseRecord->lastPerformedOrder()->count() === 0;
    }
}
