<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\NoteType;
use App\Models\Resources\Person;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Database\Seeder;

class KTAdmissionWardRegisterSeeder extends Seeder
{
    use RegistryUserAttachable;

    public function run(): void
    {
        /**
         * Step to add new registry
         * 1. Add new registry
         * 2. Add new note types
         * 3. Add new abilities and roles
         * 4. Clear ability-registry-user related cache
         * 5. Attach new abilities to root role
         * 6. Attach new registry and role to related users
         * 7. Add new actions to ResourceActionLog if needed
         */

        // 1. Add new registry
        $registry = Registry::query()->create([
            'name' => 'kt_admission',
            // 'label' => 'Kidney Transplant Ward Registry', // just for the old schema
            // 'label_eng' => 'Kidney Transplant Ward Registry', // just for the old schema
            'route' => 'wards.kt-admission.index',
            'division_id' => Division::query()->where('name_en_short', 'nephrology')->first()->id,
        ]);

        // 2. Add new note types if needed
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        /*NoteType::query()->insert([
            ['name' => 'kt_transplantation_admission_note', 'label' => 'Kidney Transplant Transplantation Admission Note'] + $timestamps,
            ['name' => 'kt_complication_admission_note', 'label' => 'Kidney Transplant Complication Admission Note'] + $timestamps,
        ]);*/

        // 3. Add new abilities and roles
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_admission_cases'] + $timestamps,
            /*// notes - index + view
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_admission_notes'] + $timestamps,*/
            // notes - create + edit + update + cancel
            ['registry_id' => $registry->id, 'name' => 'create_kt_admission_case'] + $timestamps,
            // notes - addendum
            ['registry_id' => $registry->id, 'name' => 'addendum_kt_admission_case'] + $timestamps,
        ]);
        $ktWardNurse = Role::query()->create([
            'name' => 'kt_admission_nurse',
            'label' => 'Kidney Transplant Admission Nurse',
            'registry_id' => $registry->id,
        ]);
        $ktWardNurse->abilities()
            ->attach(Ability::query()
                ->where('name', 'like', '%_kt_admission_%')
                ->select('id')
                ->pluck('id')
            );
        $ktStaff = Role::query()->create([
            'name' => 'kidney_transplant_staff',
            'label' => 'Kidney Transplant Staff',
            'registry_id' => $registry->id,
        ]);
        $ktStaff->abilities()
            ->attach(Ability::query()
                ->whereIn('name', [
                    'view_any_kt_admission_cases',
                    'addendum_kt_admission_case',
                ])
                ->select('id')
                ->pluck('id')
            );

        // 4. Clear ability-registry-user related cache
        cache()->forget('ability-registry-map');
        cache()->forget('wards-index-route-names');

        // 5. Attach new abilities to root role
        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(
            Ability::query()
                ->select('id')
                ->where('name', 'like', '%_kt_admission_%')
                ->pluck('id')
        );

        // 6. Attach new registry and roles to related users
        User::query()
            ->with('roles')
            ->whereHas('roles', fn ($query) => $query->where('name', 'acute_hemodialysis_staff'))
            ->each(function (User $user) use ($ktStaff) {
                $user->roles()->attach($ktStaff->id);
                $this->toggleRegistryUser($user);
            });
        $root->users()->with('roles')->each(fn (User $user) => $this->toggleRegistryUser($user));

        // 7. Add new actions to ResourceActionLog if needed

        // additional items
        $datetime = now();
        Division::query()
            ->insert([
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

        $division = Division::query()->where('name', 'สาขาวิชาโรคไต')->first();
        $share = [
            'position' => 8,
            'division_id' => $division->id,
            'created_at' => $datetime,
            'updated_at' => $datetime,
        ];
        Person::query()
            ->insert([
                ['name' => 'รศ.นพ. อนิรุธ ภัทรากาญจน์'] + $share,
                ['name' => 'รศ.พญ. นันทวัน ปิยะภาณี'] + $share,
                ['name' => 'ผศ.พญ. ธนพร ไชยภักดิ์'] + $share,
                ['name' => 'อ.นพ. ไกรสูรย์ ล้อมจันทร์สุข'] + $share,
                ['name' => 'พญ. ญาณ์นรินทร์ ธัญสิริพุทธิชัย'] + $share,
            ]);

        $division = Division::query()->where('name', 'สาขาวิชาศัลยศาสตร์ทั่วไป')->first();
        $share['division_id'] = $division->id;
        Person::query()
            ->insert([
                ['name' => 'รศ.นพ. ประเวชย์ มหาวิทิตวงศ์'] + $share,
                ['name' => 'รศ.นพ. เวธิต ดำรงกิตติกุล'] + $share,
                ['name' => 'ผศ.นพ. ประวัฒน์ โฆสิตะมงคล'] + $share,
                ['name' => 'ผศ.นพ. ชุติวิชัย โตวิกกัย'] + $share,
                ['name' => 'อ.นพ. พลสิทธิ์ แสงเสรีสถิตย์'] + $share,
                ['name' => 'ผศ.นพ. สมชัย ลิ้มศรีจำเริญ'] + $share,
                ['name' => 'รศ.นพ. ยงยุทธ ศิริวัฒนอักษร'] + $share,
                ['name' => 'อ.นพ. ชาญวิทย์ อัศวศิริศิลป์'] + $share,
            ]);

        $division = Division::query()->where('name', 'ศัลยศาสตร์ยูโรวิทยา')->first();
        $share['division_id'] = $division->id;
        Person::query()
            ->insert([
                ['name' => 'ผศ.นพ. ศิรส จิตประไพ'] + $share,
                ['name' => 'อ.นพ. ฐิติภัท หาญสมวงศ์'] + $share,
                ['name' => 'ผศ.นพ. วรัชญ์ วรนิสรากุล'] + $share,
                ['name' => 'พญ. กานติมา จงจิตอารี'] + $share,
                ['name' => 'รศ.นพ. ธวัชชัย ทวีมั่นคงทรัพย์'] + $share,
                ['name' => 'รศ.นพ. เอกรินทร์ โชติกวาณิชย์'] + $share,
            ]);

    }
}
