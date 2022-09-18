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

            ['registry_id' => null, 'name' => 'view_any_social_providers'] + $datetime,
            ['registry_id' => null, 'name' => 'create_social_provider'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_social_provider'] + $datetime,

            ['registry_id' => null, 'name' => 'view_any_chat_bots'] + $datetime,
            ['registry_id' => null, 'name' => 'create_chat_bot'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_chat_bot'] + $datetime,
        ]);

        Role::query()->insert([
            ['registry_id' => null, 'name' => 'root', 'label' => null] + $datetime,
            ['registry_id' => null, 'name' => 'authority', 'label' => 'Authority'] + $datetime, // authorize role to user
            ['registry_id' => null, 'name' => 'participant', 'label' => null] + $datetime,
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
