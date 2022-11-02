<?php

namespace App\Traits;

use App\Models\Resources\Registry;
use App\Models\User;

trait RegistryGroupRouteQueryable
{
    protected function getRoutesByRegistryTypeAndUser(string $registryType, User $user)
    {
        return cache()->rememberForever(
            "$registryType-index-route-names",
            fn () => Registry::query()
                ->where('route', 'like', "$registryType.%")
                ->get()
                ->transform(fn (Registry $r) => [
                    'name' => $r->name,
                    'route' => $r->route,
                ])
        )->filter(fn ($r) => $user->registry_names->contains($r['name']))->values();
    }
}
