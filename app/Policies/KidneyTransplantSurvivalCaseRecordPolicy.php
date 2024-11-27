<?php

namespace App\Policies;

use App\Enums\KidneyTransplantSurvivalCaseStatus;
use App\Models\Registries\KidneyTransplantSurvivalCaseRecord;
use App\Models\User;

class KidneyTransplantSurvivalCaseRecordPolicy
{
    public function update(User $user, KidneyTransplantSurvivalCaseRecord $case): bool
    {
        return $case->status !== KidneyTransplantSurvivalCaseStatus::DELETED
            && $user->can('update_kt_survival_case');
    }
}
