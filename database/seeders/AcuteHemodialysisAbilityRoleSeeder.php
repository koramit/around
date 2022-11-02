<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Resources\Registry;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AcuteHemodialysisAbilityRoleSeeder extends Seeder
{
    public function run(): void
    {
        $datetime = ['created_at' => now(), 'updated_at' => now()];

        $registry = Registry::query()->where('name', 'acute_hd')->first();

        Ability::query()->insert([
            // acute hd - case
            ['registry_id' => $registry->id, 'name' => 'view_any_acute_hemodialysis_cases'] + $datetime, // index
            ['registry_id' => $registry->id, 'name' => 'create_acute_hemodialysis_case'] + $datetime, // create/store
            ['registry_id' => $registry->id, 'name' => 'edit_acute_hemodialysis_case'] + $datetime, // edit/update + policy
            ['registry_id' => $registry->id, 'name' => 'view_acute_hemodialysis_case'] + $datetime, // show + policy
            ['registry_id' => $registry->id, 'name' => 'archive_acute_hemodialysis_case'] + $datetime, // archive + policy
            ['registry_id' => $registry->id, 'name' => 'cancel_acute_hemodialysis_case'] + $datetime, // destroy + policy

            // acute hd - order
            ['registry_id' => $registry->id, 'name' => 'view_any_acute_hemodialysis_orders'] + $datetime, // index [view schedule]
            ['registry_id' => $registry->id, 'name' => 'create_acute_hemodialysis_order'] + $datetime, // create/store
            ['registry_id' => $registry->id, 'name' => 'view_acute_hemodialysis_order'] + $datetime, // show + policy
            ['registry_id' => $registry->id, 'name' => 'perform_acute_hemodialysis_order'] + $datetime, // perform + policy

            // acute hd - slot request
            ['registry_id' => $registry->id, 'name' => 'view_any_acute_hemodialysis_slot_requests'] + $datetime, // index
            ['registry_id' => $registry->id, 'name' => 'approve_acute_hemodialysis_slot_request'] + $datetime, // approve + policy

            // acute hd - start dialysis session
            ['registry_id' => $registry->id, 'name' => 'start_session_days_after_date_note'] + $datetime,

            // acute hd - track md performance
            ['registry_id' => $registry->id, 'name' => 'subscribe_md_performance_notification'] + $datetime,

            // acute hd - manager stuff
            ['registry_id' => $registry->id, 'name' => 'force_complete_case'] + $datetime,
        ]);

        Role::query()->insert([
            ['registry_id' => $registry->id, 'name' => 'acute_hemodialysis_nurse', 'label' => 'Acute HD Nurse'] + $datetime,
            ['registry_id' => $registry->id, 'name' => 'acute_hemodialysis_nurse_manager', 'label' => 'Acute HD In charge nurse'] + $datetime,
            ['registry_id' => $registry->id, 'name' => 'acute_hemodialysis_fellow', 'label' => 'Acute HD Fellow'] + $datetime,
            ['registry_id' => $registry->id, 'name' => 'acute_hemodialysis_staff', 'label' => 'Acute HD Nephrologist'] + $datetime,
            ['registry_id' => $registry->id, 'name' => 'acute_hemodialysis_manager', 'label' => 'Acute HD Manager'] + $datetime,
        ]);

        $assignments = [
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
                'start_session_after_date_note',
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
            'acute_hemodialysis_staff' => [
                'subscribe_md_performance_notification',
            ],
        ];

        foreach ($assignments as $roleName => $abilities) {
            $role = Role::query()->where('name', $roleName)->first();
            $abilityIds = Ability::query()->select('id')->whereIn('name', $abilities)->pluck('id');
            $role->abilities()->attach($abilityIds);
        }

        $manager = Role::query()->where('name', 'acute_hemodialysis_manager')->first();
        $manager->abilities()
            ->attach(Ability::query()->where('registry_id', $registry->id)->pluck('id'));

        // attach abilities to root role
        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()
            ->attach(Ability::query()->where('registry_id', $registry->id)->pluck('id'));
    }
}
