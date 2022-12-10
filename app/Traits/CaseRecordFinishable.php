<?php

namespace App\Traits;

use App\Models\Resources\Patient;
use App\Models\Subscription;
use App\Models\User;

trait CaseRecordFinishable
{
    protected function finishing($caseRecord, Patient $patient, User $user, int $registryId): void
    {
        $caseRecord->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'create',
        ]);

        if ($patient->registries()->where('registry_id', $registryId)->count() === 0) {
            $patient->registries()->attach($registryId);
        }

        $sub = Subscription::query()->create([
            'subscribable_type' => $caseRecord::class,
            'subscribable_id' => $caseRecord->id,
        ]);

        if ($user->auto_subscribe_to_channel) {
            $user->subscriptions()->attach($sub->id);
        }
    }
}
