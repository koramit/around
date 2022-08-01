<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['login' => 'root.app'])->roles()->attach([1]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'manager.ahd'])->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'staff.ahd'])->roles()->attach([2, 3, 6, 7]); // authority, participant, nurse, manager;
        User::factory()
            ->count(20)
            ->create();
    }
}
