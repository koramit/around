<?php

namespace Database\Seeders;

use App\Models\EventBasedNotification;
use App\Models\Resources\Patient;
use App\Models\Resources\Registry;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\Procedures\AcuteHemodialysis\AlertOrderCancel;
use App\Notifications\Procedures\AcuteHemodialysis\AlertOrderResubmit;
use App\Notifications\Procedures\AcuteHemodialysis\AlertSessionUpdate;
use App\Notifications\Procedures\AcuteHemodialysis\AlertSlotRequest;
use Illuminate\Database\Seeder;

class AcuteHemodialysisNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $event = EventBasedNotification::query()->create([
            'name' => 'alert_order_resubmit',
            'notification_class_name' => AlertOrderResubmit::class,
            'registry_id' => 1,
            'ability_id' => 27, // perform order
        ]);
        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $event = EventBasedNotification::query()->create([
            'name' => 'alert_order_canceled',
            'notification_class_name' => AlertOrderCancel::class,
            'registry_id' => 1,
            'ability_id' => 27, // perform order
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $event = EventBasedNotification::query()->create([
            'name' => 'alert_slot_request',
            'notification_class_name' => AlertSlotRequest::class,
            'registry_id' => 1,
            'ability_id' => 29, // approve_acute_hemodialysis_slot_request
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        $event = EventBasedNotification::query()->create([
            'name' => 'alert_session_updates',
            'notification_class_name' => AlertSessionUpdate::class,
            'registry_id' => 1,
            'ability_id' => 25, // create_acute_hemodialysis_order
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);

        Registry::query()
            ->first()
            ->patients()
            ->sync(Patient::query()->pluck('id'));

        Registry::query()
            ->first()
            ->users()
            ->sync(User::query()->pluck('id'));

        User::query()->update(['preferences->mute' => false]);
        User::query()->update(['preferences->notify_approval_result' => true]);
        User::query()->update(['preferences->auto_subscribe_to_channel' => false]);
        User::query()->update(['preferences->auto_unsubscribe_to_channel' => true]);

        /** additional */
        // order approved/disapproved CHECKED
        // tomorrow order not complete 20:00/20:30
        // order started CHECKED
        // order finished CHECKED
        // case d/c
        // case not complete after d/c or idle for two weeks
    }
}
