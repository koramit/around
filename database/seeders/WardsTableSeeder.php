<?php

namespace Database\Seeders;

use App\Models\Resources\Ward;
use App\Traits\CSVLoader;
use Illuminate\Database\Seeder;

class WardsTableSeeder extends Seeder
{
    use CSVLoader;

    public function run(): void
    {
        $this->seed(path: storage_path('app/seeders/wards.csv'), className: Ward::class);
    }
}
