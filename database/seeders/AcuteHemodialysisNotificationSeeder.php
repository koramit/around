<?php

namespace Database\Seeders;

use App\Models\EventBasedNotification;
use App\Models\Subscription;
use App\Notifications\Procedures\AcuteHemodialysis\AlertOrderResubmit;
use Illuminate\Database\Seeder;

class AcuteHemodialysisNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $event = EventBasedNotification::query()->create([
            'name' => 'alert_order_resubmit',
            'class_name' => AlertOrderResubmit::class,
            'locale' => [
                'en' => [
                    'label' => 'alert order change',
                    'detail' => 'yes',
                ],
                'th' => [
                    'label' => 'alert order change',
                    'detail' => 'yes',
                ],
            ],
            'registry_id' => 1,
            'ability_id' => 27,
        ]);

        Subscription::query()->create([
            'subscribable_type' => $event::class,
            'subscribable_id' => $event->id,
        ]);
    }
}
