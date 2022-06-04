<?php

namespace Database\Seeders;

use App\Models\Resources\Division;
use App\Models\Resources\Registry;
use Illuminate\Database\Seeder;

class RegistriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = now();

        Registry::insert([
            [
                'name' => 'acute_hd',
                'label' => 'Acute Hemodialysis',
                'label_eng' => 'Acute Hemodialysis',
                'route' => 'procedures.acute-hemodialysis.',
                'division_id' => Division::where('name_eng_short', 'nephrology')->first()->id,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
        ]);
    }
}
