<?php

namespace Database\Seeders;

use App\Models\Resources\NoteType;
use Illuminate\Database\Seeder;

class NoteTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = now();

        NoteType::insert([
            [
                'name' => 'acute_hd_order',
                'label' => 'Acute Hemodialysis Order',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
        ]);
    }
}
