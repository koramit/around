<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AbilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $datetime = ['created_at' => now(), 'updated_at' => now()];

        Ability::query()->insert([
            ['registry_id' => null, 'name' => 'view_any_abilities'] + $datetime,
            ['registry_id' => null, 'name' => 'create_ability'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_ability'] + $datetime,
            ['registry_id' => null, 'name' => 'cancel_ability'] + $datetime,

            ['registry_id' => null, 'name' => 'view_any_roles'] + $datetime,
            ['registry_id' => null, 'name' => 'create_role'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_role'] + $datetime,
            ['registry_id' => null, 'name' => 'allow_ability_to_role'] + $datetime,
            ['registry_id' => null, 'name' => 'cancel_role'] + $datetime,

            ['registry_id' => null, 'name' => 'authorize_user'] + $datetime,
            ['registry_id' => null, 'name' => 'authorize_authority'] + $datetime,

            ['registry_id' => null, 'name' => 'view_any_patients'] + $datetime,
            ['registry_id' => null, 'name' => 'config_preferences'] + $datetime,
            ['registry_id' => null, 'name' => 'get_support'] + $datetime,
            ['registry_id' => null, 'name' => 'get_shared_api_resources'] + $datetime,
            ['registry_id' => null, 'name' => 'upload_file'] + $datetime,
            ['registry_id' => null, 'name' => 'comment'] + $datetime,

            // acute hd case
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_cases'] + $datetime, // index
            ['registry_id' => 1, 'name' => 'create_acute_hemodialysis_case'] + $datetime, // create/store
            ['registry_id' => 1, 'name' => 'edit_acute_hemodialysis_case'] + $datetime, // edit/update + policy
            ['registry_id' => 1, 'name' => 'view_acute_hemodialysis_case'] + $datetime, // show + policy
            ['registry_id' => 1, 'name' => 'archive_acute_hemodialysis_case'] + $datetime, // archive + policy
            ['registry_id' => 1, 'name' => 'cancel_acute_hemodialysis_case'] + $datetime, // destroy + policy

            // acute hd order
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_orders'] + $datetime, // index [view schedule]
            ['registry_id' => 1, 'name' => 'create_acute_hemodialysis_order'] + $datetime, // create/store
            ['registry_id' => 1, 'name' => 'view_acute_hemodialysis_order'] + $datetime, // show + policy
            ['registry_id' => 1, 'name' => 'perform_acute_hemodialysis_order'] + $datetime, // perform + policy

            // slot request
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_slot_requests'] + $datetime, // index
            ['registry_id' => 1, 'name' => 'approve_acute_hemodialysis_slot_request'] + $datetime, // approve + policy
        ]);

        Role::query()->insert([
            ['registry_id' => null, 'name' => 'root', 'label' => null] + $datetime,
            ['registry_id' => null, 'name' => 'authority', 'label' => 'Authority'] + $datetime, // authorize role to user
            ['registry_id' => null, 'name' => 'participant', 'label' => null] + $datetime,

            ['registry_id' => 1, 'name' => 'acute_hemodialysis_nurse', 'label' => 'Acute HD nurse'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_nurse_manager', 'label' => 'Acute HD in charge nurse'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_fellow', 'label' => 'Acute HD fellow'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_staff', 'label' => 'Acute HD nephrologist'] + $datetime,
        ]);

        $assignments = [
            'authority' => ['authorize_user'],
            'participant' => [
                'view_any_patients',
                'upload_file',
                'get_shared_api_resources',
                'config_preferences',
                'get_support',
                'comment',
            ],
            'acute_hemodialysis_nurse' => [
                'view_any_acute_hemodialysis_cases',
                'view_acute_hemodialysis_case',
                'view_any_acute_hemodialysis_orders',
                'view_acute_hemodialysis_order',
                'perform_acute_hemodialysis_order',
                'view_any_acute_hemodialysis_slot_requests',
            ],
            'acute_hemodialysis_nurse_manager' => [
                'approve_acute_hemodialysis_slot_request',
                'create_acute_hemodialysis_case',
            ],
            'acute_hemodialysis_fellow' => [
                'view_any_acute_hemodialysis_cases',
                'create_acute_hemodialysis_case',
                'edit_acute_hemodialysis_case',
                'view_acute_hemodialysis_case',
                'archive_acute_hemodialysis_case',
                'cancel_acute_hemodialysis_case',
                'view_any_acute_hemodialysis_orders',
                'create_acute_hemodialysis_order',
                'view_acute_hemodialysis_order',
                'view_any_acute_hemodialysis_slot_requests',
            ],
        ];

        foreach ($assignments as $roleName => $abilities) {
            $role = Role::query()->where('name', $roleName)->first();
            $abilityIds = Ability::query()->select('id')->whereIn('name', $abilities)->pluck('id');
            $role->abilities()->attach($abilityIds);
        }

        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(Ability::query()->select('id')->pluck('id'));

        User::query()->create([
            'name' => 'around',
            'login' => 'around.app',
            'division_id' => 1,
            'full_name' => 'Around System',
            'profile' => [
                'tel_no' => 'HELPDESK',
                'org_id' => 1,
                'division' => 'system',
                'position' => 'program',
                'pln' => null,
                'remark' => 'system program',
            ],
            'password' => Hash::make(Str::random(10)),
            'remember_token' => Str::random(10),
        ]);
    }
}
