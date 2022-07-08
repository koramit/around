<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AbilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = ['created_at' => now(), 'updated_at' => now()];

        Ability::insert([
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
            ['registry_id' => null, 'name' => 'get_shared_api_resources'] + $datetime,
            ['registry_id' => null, 'name' => 'upload_files'] + $datetime,

            // acute hd case
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_cases'] + $datetime, // index
            ['registry_id' => 1, 'name' => 'create_acute_hemodialysis_case'] + $datetime, // create/store
            ['registry_id' => 1, 'name' => 'edit_acute_hemodialysis_case'] + $datetime, // edit/update + policy
            ['registry_id' => 1, 'name' => 'view_acute_hemodialysis_case'] + $datetime, // show + policy
            ['registry_id' => 1, 'name' => 'archive_acute_hemodialysis_case'] + $datetime, // archive + policy
            ['registry_id' => 1, 'name' => 'cancel_acute_hemodialysis_case'] + $datetime, // destroy + policy

            // acute hd order
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_notes'] + $datetime, // index [view schedule]
            ['registry_id' => 1, 'name' => 'create_acute_hemodialysis_note'] + $datetime, // create/store
            ['registry_id' => 1, 'name' => 'view_acute_hemodialysis_note'] + $datetime, // show + policy
            ['registry_id' => 1, 'name' => 'perform_acute_hemodialysis_note'] + $datetime, // perform + policy
            // ['registry_id' => 1, 'name' => 'cancel_acute_hemodialysis_note'] + $datetime, // destroy + policy only!
            // ['registry_id' => 1, 'name' => 'edit_acute_hemodialysis_note'] + $datetime, // edit/update + policy only!

            // today slot request
            ['registry_id' => 1, 'name' => 'view_any_acute_hemodialysis_slot_requests'] + $datetime, // index
            ['registry_id' => 1, 'name' => 'create_acute_hemodialysis_today_slot_request'] + $datetime, // schedule/reschedule/swap with today invole
            ['registry_id' => 1, 'name' => 'approve_acute_hemodialysis_today_slot_request'] + $datetime, // approve + policy
            // ['registry_id' => 1, 'name' => 'cancel_acute_hemodialysis_today_slot_request'] + $datetime, // destroy + policy only!
            // ['registry_id' => 1, 'name' => 'edit_acute_hemodialysis_today_slot_request'] + $datetime, // no edit just destroy
            // ['registry_id' => 1, 'name' => 'view_acute_hemodialysis_today_slot_request'] + $datetime, // no show just detail in index
        ]);

        Role::insert([
            ['registry_id' => null, 'name' => 'root', 'label' => null] + $datetime,
            ['registry_id' => null, 'name' => 'authority', 'label' => null] + $datetime, // authorize role to user
            ['registry_id' => null, 'name' => 'participant', 'label' => null] + $datetime,

            ['registry_id' => 1, 'name' => 'acute_hemodialysis_nurse', 'label' => 'Aucte HD nurse'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_nurse_manager', 'label' => 'Acute HD in charge nurse'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_fellow', 'label' => 'Acute HD fellow'] + $datetime,
            ['registry_id' => 1, 'name' => 'acute_hemodialysis_staff', 'label' => 'Acute HD nephrologist'] + $datetime,
        ]);

        $assignments = [
            'authority' => ['authorize_user'],
            'participant' => ['view_any_patients', 'get_shared_api_resources'],
            'acute_hemodialysis_nurse' => [
                'view_any_acute_hemodialysis_cases',
                'view_acute_hemodialysis_case',
                'view_any_acute_hemodialysis_notes',
                'view_acute_hemodialysis_note',
                'perform_acute_hemodialysis_note',
                'view_any_acute_hemodialysis_slot_requests',
            ],
            'acute_hemodialysis_nurse_manager' => [
                'approve_acute_hemodialysis_today_slot_request',
            ],
            'acute_hemodialysis_fellow' => [
                'upload_files',
                'view_any_acute_hemodialysis_cases',
                'create_acute_hemodialysis_case',
                'edit_acute_hemodialysis_case',
                'view_acute_hemodialysis_case',
                'archive_acute_hemodialysis_case',
                'cancel_acute_hemodialysis_case',
                'view_any_acute_hemodialysis_notes',
                'create_acute_hemodialysis_note',
                'view_acute_hemodialysis_note',
                'view_any_acute_hemodialysis_slot_requests',
                'create_acute_hemodialysis_today_slot_request',
            ],
        ];

        foreach ($assignments as $roleName => $abilities) {
            $role = Role::query()->where('name', $roleName)->first();
            $abilityIds = Ability::query()->select('id')->whereIn('name', $abilities)->pluck('id');
            $role->abilities()->attach($abilityIds);
        }

        $root = Role::query()->where('name', 'root')->first();
        $root->abilities()->attach(Ability::query()->select('id')->pluck('id'));
    }
}