<?php

namespace Database\Seeders;

use App\Models\Resources\Person;
use App\Traits\CSVLoader;
use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    use CSVLoader;

    public function run(): void
    {
        $this->seed(path: storage_path('app/seeders/people.csv'), className: Person::class);
    }
}
