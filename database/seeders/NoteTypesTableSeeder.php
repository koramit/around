<?php

namespace Database\Seeders;

use App\Models\Resources\NoteType;
use Illuminate\Database\Seeder;

class NoteTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        $datetime = now();

        NoteType::query()->insert([
            [
                'name' => 'acute_hd_order',
                'label' => 'Acute Hemodialysis Order',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
        ]);
    }
}
