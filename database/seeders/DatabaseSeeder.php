<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            DivisionsTableSeeder::class,
            WardsTableSeeder::class,
            PeopleTableSeeder::class,
            NoteTypesTableSeeder::class,
            RegistriesTableSeeder::class,
            AbilitiesTableSeeder::class,
            NotificationFeatureSeeder::class,
            AcuteHemodialysisNotificationSeeder::class,
        ]);
    }
}
