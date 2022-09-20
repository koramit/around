<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\EventBasedNotification;
use App\Models\Resources\Registry;
use App\Models\Subscription;
use App\Notifications\Procedures\AcuteHemodialysis\AlertOrderCancel;
use App\Notifications\Procedures\AcuteHemodialysis\AlertOrderResubmit;
use App\Notifications\Procedures\AcuteHemodialysis\AlertSessionUpdate;
use App\Notifications\Procedures\AcuteHemodialysis\AlertSlotRequest;
use App\Notifications\Procedures\AcuteHemodialysis\IncompleteCaseDailyReport;
use Illuminate\Database\Seeder;

class AcuteHemodialysisEventNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $registry = Registry::query()->where('name', 'acute_hd')->first();
        $ability = Ability::query()->where('name', 'perform_acute_hemodialysis_order')->first();
        $event = EventBasedNotification::query()->create([
            'name' => 'alert_order_resubmit',
            'notification_class_name' => AlertOrderResubmit::class,
            'registry_id' => $registry->id,
            'ability_id' => $ability->id, // perform order
        ]);
        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $event = EventBasedNotification::query()->create([
            'name' => 'alert_order_canceled',
            'notification_class_name' => AlertOrderCancel::class,
            'registry_id' => $registry->id,
            'ability_id' => $ability->id, // perform order
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $ability = Ability::query()->where('name', 'approve_acute_hemodialysis_slot_request')->first();
        $event = EventBasedNotification::query()->create([
            'name' => 'alert_slot_request',
            'notification_class_name' => AlertSlotRequest::class,
            'registry_id' => $registry->id,
            'ability_id' => $ability->id, // approve_acute_hemodialysis_slot_request
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $ability = Ability::query()->where('name', 'create_acute_hemodialysis_order')->first();
        $event = EventBasedNotification::query()->create([
            'name' => 'alert_session_updates',
            'notification_class_name' => AlertSessionUpdate::class,
            'registry_id' => $registry->id,
            'ability_id' => $ability->id, // create_acute_hemodialysis_order
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $ability = Ability::query()->where('name', 'subscribe_md_performance_notification')->first();
        $event = EventBasedNotification::query()->create([
            'name' => 'incomplete_case_daily_report',
            'notification_class_name' => IncompleteCaseDailyReport::class,
            'registry_id' => $registry->id,
            'ability_id' => $ability->id,
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);
    }
}
