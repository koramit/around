<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\NoteType;
use App\Models\Resources\Registry;
use App\Models\Role;
use App\Models\User;
use App\Traits\RegistryUserAttachable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KTAdmissionWardRegisterSeeder extends Seeder
{
    use RegistryUserAttachable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Step to add new registry
         * 1. Add new registry
         * 2. Add new note types
         * 3. Add new abilities and roles
         * 4. Clear ability-registry-user related cache
         * 5. Attach new abilities to root role
         * 6. Attach new registry to root user
         * 7. Add new actions to ResourceActionLog if needed
         */

        // 1. Add new registry
        $registry = Registry::query()->create([
            'name' => 'kt_ward_registry',
            // 'label' => 'Kidney Transplant Ward Registry', // just for the old schema
            // 'label_eng' => 'Kidney Transplant Ward Registry', // just for the old schema
            'route' => 'wards.kt-ward-registry.index',
            'division_id' => Division::query()->where('name_en_short', 'nephrology')->first()->id,
        ]);

        // 2. Add new note types
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        NoteType::query()->insert([
            ['name' => 'kt_transplantation_admission_note', 'label' => 'Kidney Transplant Transplantation Admission Note'] + $timestamps,
            ['name' => 'kt_complication_admission_note', 'label' => 'Kidney Transplant Complication Admission Note'] + $timestamps,
        ]);

        // 3. Add new abilities and roles
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_ward_registry_cases'] + $timestamps,
            // notes - index + view
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_ward_registry_notes'] + $timestamps,
            // notes - create + edit + update + cancel
            ['registry_id' => $registry->id, 'name' => 'create_kt_ward_registry_notes'] + $timestamps,
            // notes - addendum
            ['registry_id' => $registry->id, 'name' => 'addendum_kt_ward_registry_notes'] + $timestamps,
        ]);

        // 4. Clear ability-registry-user related cache
        $ktWardNurse = Role::query()->create([
            'name' => 'kt_ward_registry_nurse',
            'label' => 'Kidney Transplant Ward Nurse',
            'registry_id' => $registry->id,
        ]);

        // 5. Attach new abilities to root role
        $ktWardNurse->abilities()
            ->attach(Ability::query()
                ->where('name', 'like', '%_kt_ward_registry_%')
                ->select('id')
                ->pluck('id')
            );

        // 6. Attach new registry to root user
        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(
            Ability::query()
                ->select('id')
                ->where('name', 'like', '%_kt_ward_registry_%')
                ->pluck('id')
        );

        // clear ability-registry-user related cache
        cache()->forget('ability-registry-map');
        cache()->forget('clinics-index-route-names');
        cache()->forget('procedures-index-route-names');
        cache()->forget('labs-index-route-names');

        // attach coordinator role to existing acute hemodialysis staffs
        User::query()
            ->with('roles')
            ->whereHas('roles', fn ($query) => $query->where('name', 'acute_hemodialysis_staff'))
            ->each(function (User $user) use ($ktWardNurse) {
                $user->roles()->attach($ktWardNurse->id);
                $this->toggleRegistryUser($user);
            });

        $root->users()->with('roles')->each(fn (User $user) => $this->toggleRegistryUser($user));

        // 7. Add new actions to ResourceActionLog if needed
    }
}
