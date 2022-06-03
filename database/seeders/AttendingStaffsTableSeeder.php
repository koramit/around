<?php

namespace Database\Seeders;

use App\Models\Resources\AttendingStaff;
use App\Traits\CSVLoader;
use Illuminate\Database\Seeder;

class AttendingStaffsTableSeeder extends Seeder
{
    use CSVLoader;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(path: storage_path('app/seeders/staffs.csv'), className: AttendingStaff::class);
    }
}
