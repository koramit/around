<?php

namespace App\Traits;

use App\Models\Ability;
use App\Models\User;

trait RegistryUserAttachable
{
    protected function toggleRegistryUser(User $user): void
    {
        $user->flushPrivileges();

        $registries = cache()->rememberForever(
            'ability-registry-map',
            fn () => Ability::query()
                ->select(['name', 'registry_id'])
                ->where('name', 'like', 'view_any_%_cases')
                ->whereNotNull('registry_id')
                ->pluck('name', 'registry_id')
        );

        foreach ($registries as $registryId => $registry) {
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
