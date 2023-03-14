<?php

namespace Database\Seeders;

use App\Models\Resources\Division;
use Illuminate\Database\Seeder;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $datetime = now();

        Division::query()->insert([
            [
                'name' => 'system',
                'name_en' => 'system',
                'name_en_short' => 'system',
                'department' => 'system',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'คณะแพทยศาสตร์ศิริราชพยาบาล',
                'name_en' => 'Faculty of Medicine Siriraj Hospital',
                'name_en_short' => 'faculty',
                'department' => 'faculty',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ภาควิชาอายุรศาสตร์',
                'name_en' => 'Department of Medicine',
                'name_en_short' => 'medicine',
                'department' => 'medicine',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ฝ่ายเภสัชกรรม',
                'name_en' => 'Pharmacy Department',
                'name_en_short' => 'pharmacy',
                'department' => 'pharmacy',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ฝ่ายการพยาบาล',
                'name_en' => 'Department of Nursing',
                'name_en_short' => 'nursing',
                'department' => 'nursing',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'วักกะวิทยา',
                'name_en' => 'Division of Nephrology',
                'name_en_short' => 'nephrology',
                'department' => 'medicine',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ภาควิชากุมารเวชศาสตร์',
                'name_en' => 'Department of Pediatrics',
                'name_en_short' => 'pediatrics',
                'department' => 'pediatrics',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ภาควิชาศัลยศาสตร์',
                'name_en' => 'Department of Surgery',
                'name_en_short' => 'surgery',
                'department' => 'surgery',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'สาขาวิชาโรคไต',
                'name_en' => 'Division of Nephrology',
                'name_en_short' => 'nephrology',
                'department' => 'pediatrics',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'สาขาวิชาศัลยศาสตร์ทั่วไป',
                'name_en' => 'Division of General Surgery',
                'name_en_short' => 'general surgery',
                'department' => 'surgery',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
            [
                'name' => 'ศัลยศาสตร์ยูโรวิทยา',
                'name_en' => 'Division of Urology',
                'name_en_short' => 'urology',
                'department' => 'surgery',
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ],
        ]);
    }
}
