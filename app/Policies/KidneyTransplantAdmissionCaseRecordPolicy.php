<?php

namespace App\Policies;

use App\Models\Registries\KidneyTransplantAdmissionCaseRecord;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KidneyTransplantAdmissionCaseRecordPolicy
{
    use HandlesAuthorization;

    public function edit(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && $caseRecord->status === 'draft';
    }

    public function update(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && $caseRecord->status === 'draft';
    }

    public function complete(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && $caseRecord->status === 'draft';
    }

    public function destroy(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && $caseRecord->status === 'draft';
    }

    public function addendum(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && collect(['completed', 'edited'])->contains($caseRecord->status);
    }

    public function cancel(User $user, KidneyTransplantAdmissionCaseRecord $caseRecord): bool
    {
        return $user->can('create_kt_admission_case')
            && collect(['completed', 'edited'])->contains($caseRecord->status);
    }
}
