<?php

namespace App\Actions\User;

use App\Models\Role;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\RegistryUserAttachable;

class UserRoleUpdateAction
{
    use AvatarLinkable, RegistryUserAttachable;

    public function __invoke(string $hashedKey, array $roles, mixed $authority)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        // user
        $user = User::query()->findByUnhashKey($hashedKey)->firstOrFail();

        // granted roles
        $checked = collect($roles)->filter(fn ($r) => $r['has_role'])
            ->values()
            ->pluck('name')
            ->filter(fn ($name) => $user->role_labels->doesntContain($name))
            ->values();
        if ($checked->count()) {
            $user->actionLogs()->create([
                'action' => 'grant',
                'actor_id' => $authority->id,
                'payload' => ['roles' => $checked->all()],
            ]);
        }
        $ids = Role::query()->whereIn('label', $checked)->pluck('id');
        if ($user->role_labels->count() === 0) {
            $ids[] = 3; // participant
        }
        $user->roles()->attach($ids);

        // revoked roles
        $notChecked = collect($roles)->filter(fn ($r) => ! $r['has_role'])
            ->values()
            ->pluck('name')
            ->filter(fn ($name) => $user->role_labels->contains($name))
            ->values();
        if ($notChecked->count()) {
            $user->actionLogs()->create([
                'action' => 'grant',
                'actor_id' => $authority->id,
                'payload' => ['roles' => $notChecked->all()],
            ]);
        }
        $user->roles()->detach(Role::query()->whereIn('label', $notChecked)->pluck('id'));
        if ($user->roles()->count() === 1) {
            $user->roles()->detach();
        }
        $user->flushPrivileges();

        $this->toggleRegistryUser($user);

        return ['ok' => true];
    }
}
