<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    use RegistryUserAttachable;

    public function run(): void
    {
        cache()->forget('ability-registry-map');

        $user = User::factory()->create(['login' => 'root.app']);
        $user->roles()->attach([1]); // authority, participant, nurse, manager;
        $this->toggleRegistryUser($user);

        $user = User::factory()->create(['login' => 'manager.ahd']);
        $user->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        $this->toggleRegistryUser($user);

        $user = User::factory()->create(['login' => 'staff.ahd']);
        $user->roles()->attach([2, 3, 6, 7]); // authority, participant, nurse, manager;
        $this->toggleRegistryUser($user);

        User::factory()
            ->count(20)
            ->create();
    }
}
