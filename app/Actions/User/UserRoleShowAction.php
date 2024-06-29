<?php

namespace App\Actions\User;

use App\Models\Role;
use App\Models\User;
use App\Traits\AvatarLinkable;

class UserRoleShowAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, mixed $authority)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $user = User::query()->findByUnhashKey($hashedKey)->firstOrFail();
        cache()->forget("uid-$user->id-role-labels");

        if ($authority->can('authorize_authority')) {
            $roles = Role::query()
                ->select('label')
                ->whereNotNull('label') // null label roles are higher level role
                ->orderBy('id')
                ->pluck('label')
                ->map(fn ($r) => ['name' => $r, 'has_role' => $user->role_labels->contains($r)]);
        } else {
            $roles = $authority->role_labels
                ->map(fn ($r) => ['name' => $r, 'has_role' => $user->role_labels->contains($r)])
                ->filter(fn ($r) => strtolower($r['name']) !== 'authority')
                ->values();
        }

        return [
            'name' => $user->full_name,
            'division' => $user->profile['division'],
            'position' => $user->profile['position'],
            'remark' => $user->profile['remark'],
            'roles' => $roles,
            'update_route' => route('users.roles.update', $user->hashed_key),
        ];
    }
}
