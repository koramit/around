<?php

namespace App\Traits;

use App\Models\Ability;
use App\Models\User;

trait RegistryUserAttachable
{
    protected function toggleRegistryUser(User $user): void
    {
        $registries = ['view_any_acute_hemodialysis_cases'];
        foreach ($registries as $registry) {
            $registryId = Ability::query()
                ->select('registry_id')
                ->where('name', $registry)
                ->first()
                ->registry_id;
            if ($user->abilities->contains($registry)) {
                if ($user->registries()->where('registry_id', $registryId)->count() === 0) {
                    $user->registries()->attach($registryId);
                }
            } else {
                if ($user->registries()->where('registry_id', $registryId)->count() !== 0) {
                    $user->registries()->detach($registryId);
                }
            }

            cache()->forget("uid-$user->id-registry-names");
        }
    }
}
