<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DivisionsTableSeeder::class,
            WardsTableSeeder::class,
            PeopleTableSeeder::class,
            NoteTypesTableSeeder::class,
            RegistriesTableSeeder::class,
            AbilitiesTableSeeder::class,
            AcuteHemodialysisAbilityRoleSeeder::class,
            AcuteHemodialysisEventNotificationSeeder::class,
            LabKTHLATypingFeatureSeeder::class,
            KTAdmissionWardRegisterSeeder::class,
        ]);
    }
}
