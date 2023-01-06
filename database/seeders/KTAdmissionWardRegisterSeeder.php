<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Division;
use App\Models\Resources\NoteType;
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

        // 2. Add new note types
        $timestamps = ['created_at' => now(), 'updated_at' => now()];
        NoteType::query()->insert([
            ['name' => 'kt_transplantation_admission_note', 'label' => 'Kidney Transplant Transplantation Admission Note'] + $timestamps,
            ['name' => 'kt_complication_admission_note', 'label' => 'Kidney Transplant Complication Admission Note'] + $timestamps,
        ]);

        // 3. Add new abilities and roles
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_admission_cases'] + $timestamps,
            // notes - index + view
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_admission_notes'] + $timestamps,
            // notes - create + edit + update + cancel
            ['registry_id' => $registry->id, 'name' => 'create_kt_admission_note'] + $timestamps,
            // notes - addendum
            ['registry_id' => $registry->id, 'name' => 'addendum_kt_admission_note'] + $timestamps,
        ]);
        $ktWardNurse = Role::query()->create([
            'name' => 'kt_admission_nurse',
            'label' => 'Kidney Transplant Ward Nurse',
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
                    'view_any_kt_admission_notes',
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
    }
}
