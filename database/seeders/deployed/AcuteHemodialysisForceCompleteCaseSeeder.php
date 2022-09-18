<?php

namespace Database\Seeders\deployed;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AcuteHemodialysisForceCompleteCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ability = Ability::query()->create([
            'registry_id' => 1,
            'name' => 'force_complete_case',
        ]);

        $root = Role::query()
            ->where('name', 'root')
            ->first();
        $root->allowTo($ability);

        $manager = Role::query()
            ->where('name', 'acute_hemodialysis_manager')
            ->first();
        $manager->allowTo($ability);
    }
}
