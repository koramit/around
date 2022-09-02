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

    public function complete(User $user, AcuteHemodialysisCaseRecord $caseRecord): bool
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return $user->can('edit_acute_hemodialysis_case')
            && $caseRecord->orders()->performedStatuses()->count()
            && (
                collect(['discharged', 'dismissed'])->contains($caseRecord->status)
                || (
                    $caseRecord->status === 'active'
                    && (
                        $caseRecord->orders()->ActiveStatuses()->count() === 0
                        || !$caseRecord->meta['an']
                    )
                )
            );
    }

    public function addendum(User $user, AcuteHemodialysisCaseRecord $caseRecord): bool
    {
        return $user->can('edit_acute_hemodialysis_case')
            && $caseRecord->status === 'completed';
    }
}
