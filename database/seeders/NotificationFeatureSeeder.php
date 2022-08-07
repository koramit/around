<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;

class NotificationFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datetime = ['created_at' => now(), 'updated_at' => now()];

        Ability::query()->insert([
            ['registry_id' => null, 'name' => 'view_any_social_providers'] + $datetime,
            ['registry_id' => null, 'name' => 'create_social_provider'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_social_provider'] + $datetime,

            ['registry_id' => null, 'name' => 'view_any_chat_bots'] + $datetime,
            ['registry_id' => null, 'name' => 'create_chat_bot'] + $datetime,
            ['registry_id' => null, 'name' => 'edit_chat_bot'] + $datetime,

            ['registry_id' => null, 'name' => 'view_user_full_profile'] + $datetime, // helpdesk
            ['registry_id' => null, 'name' => 'edit_user_config'] + $datetime, // division + line bot provider
            ['registry_id' => null, 'name' => 'disable_user'] + $datetime,
        ]);
    }
}
