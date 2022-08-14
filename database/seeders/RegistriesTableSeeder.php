<?php

namespace Database\Seeders;

use App\Models\Resources\Division;
use App\Models\Resources\Registry;
use Illuminate\Database\Seeder;

class RegistriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $datetime = now();

        Registry::query()->insert([
            [
                'name' => 'acute_hd',
                'route' => route('procedures.acute-hemodialysis.index'),
                'division_id' => Division::query()->where('name_en_short', 'nephrology')->first()->id,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
        ]);
    }
}
