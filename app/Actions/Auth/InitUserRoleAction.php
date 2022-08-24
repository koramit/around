<?php

namespace App\Actions\Auth;

use App\Models\Resources\Division;
use App\Models\Role;
use App\Models\User;
use App\Traits\CSVLoader;
use App\Traits\RegistryUserAttachable;

class InitUserRoleAction
{
    use CSVLoader, RegistryUserAttachable;

    public function __invoke(User $user): bool
    {
        $list = $this->loadCSV(storage_path('app/seeders/users.csv'));

        $index = collect($list)->search(fn ($u) => $u['ref_id'] == $user->profile['org_id']);

        if ($index === false) {
            return false;
        }

        $roles = explode('|', $list[$index]['roles']);
        $user->roles()->attach(Role::query()->select('id')->whereIn('name', $roles)->pluck('id'));

        // attach registry
        $this->toggleRegistryUser($user);

        return $user->update([
            'division_id' => Division::query()->select('id')->where('name_en_short', $list[$index]['division'])->first()->id,
            'profile->pln' => $list[$index]['pln'] ?? null,
        ]);
    }
}
