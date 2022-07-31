<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AuthorizationSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['login' => 'nurse1.ahd'])->roles()->attach([3, 4]); // participant, nurse;
        User::factory()->create(['login' => 'nurse2.ahd'])->roles()->attach([3, 4]); // participant, nurse;
        User::factory()->create(['login' => 'manager1.ahd'])->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'manager2.ahd'])->roles()->attach([2, 3, 4, 5]); // authority, participant, nurse, manager;
        User::factory()->create(['login' => 'fellow1.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow2.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow3.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'fellow4.ahd'])->roles()->attach([3, 6]); // participant, fellow;
        User::factory()->create(['login' => 'staff1.ahd'])->roles()->attach([2, 3, 6, 7]); // authority, participant, fellow, staff;
        User::factory()->create(['login' => 'staff2.ahd'])->roles()->attach([2, 3, 6, 7]); // authority, participant, fellow, staff;
        User::factory()
            ->count(20)
            ->create();
    }
}
