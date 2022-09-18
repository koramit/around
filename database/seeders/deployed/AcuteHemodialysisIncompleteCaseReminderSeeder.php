<?php

namespace Database\Seeders\deployed;

use App\Models\Ability;
use App\Models\EventBasedNotification;
use App\Models\Role;
use App\Models\Subscription;
use App\Notifications\Procedures\AcuteHemodialysis\IncompleteCaseDailyReport;
use Illuminate\Database\Seeder;

class AcuteHemodialysisIncompleteCaseReminderSeeder extends Seeder
{
    public function run(): void
    {
        $reminder = Ability::query()->create([
            'registry_id' => 1,
            'name' => 'subscribe_md_performance_notification',
        ]);
        $root = Role::query()
            ->where('name', 'root')
            ->first();
        $root->allowTo($reminder);
        $staff = Role::query()
            ->where('name', 'acute_hemodialysis_staff')
            ->first();
        $staff->allowTo($reminder);
        $appManager = Role::query()->create([
            'name' => 'acute_hemodialysis_manager',
            'label' => 'Acute HD Manager',
        ]);
        $appManager->abilities()
            ->attach(Ability::query()->where('registry_id', 1)->pluck('id'));

        $event = EventBasedNotification::query()->create([
            'name' => 'incomplete_case_daily_report',
            'notification_class_name' => IncompleteCaseDailyReport::class,
            'registry_id' => 1,
            'ability_id' => $reminder->id,
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);
    }
}
