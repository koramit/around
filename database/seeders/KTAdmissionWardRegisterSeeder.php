<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Division;
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
         * 8. Add new resources if needed
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

        // 3. Add new abilities and roles
        Ability::query()->insert([
            // cases - index
            ['registry_id' => $registry->id, 'name' => 'view_any_kt_admission_cases'] + $timestamps,
            // cases - create
            ['registry_id' => $registry->id, 'name' => 'create_kt_admission_case'] + $timestamps,
            // cases - addendum
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
        $ktFellow = Role::query()->create([
            'name' => 'kt_admission_fellow',
            'label' => 'Kidney Transplant Admission Fellow',
            'registry_id' => $registry->id,
        ]);
        $ktFellow->abilities()
            ->attach(Ability::query()
                ->whereIn('name', [
                    'view_any_kt_admission_cases',
                    'addendum_kt_admission_case',
                ])
                ->select('id')
                ->pluck('id')
            );
        $ktStaff = Role::query()->create([
            'name' => 'kt_admission_staff',
            'label' => 'Kidney Transplant Admission Staff',
            'registry_id' => $registry->id,
        ]);

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
        $root->users()->with('roles')->each(fn (User $user) => $this->toggleRegistryUser($user));

        // 6. Attach new registry and roles to related users
        User::query()
            ->with('roles')
            ->whereHas('roles', fn ($query) => $query->where('name', 'acute_hemodialysis_staff'))
            ->each(function (User $user) use ($ktStaff, $ktFellow) {
                $user->roles()->attach([$ktFellow->id, $ktStaff->id]);
                $this->toggleRegistryUser($user);
            });
        User::query()
            ->with('roles')
            ->whereHas('roles', fn ($query) => $query->where('name', 'acute_hemodialysis_fellow'))
            ->each(function (User $user) use ($ktFellow) {
                if ($user->roles->where('name', 'acute_hemodialysis_staff')->isEmpty()) {
                    $user->roles()->attach($ktFellow->id);
                    $this->toggleRegistryUser($user);
                }
            });

        // 7. Add new actions to ResourceActionLog if needed

        // 8. Add new resources if needed
    }
}
