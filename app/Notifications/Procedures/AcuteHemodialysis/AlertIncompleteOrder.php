<?php

namespace App\Notifications\Procedures\AcuteHemodialysis;

use App\Contracts\MessagingApp;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class AlertIncompleteOrder extends Notification implements MessagingApp
{
    use SocialAppMessagable;

    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $this->message = "Order เคส {$order->meta['name']} ยังเขียนไม่เสร็จ";
        $this->magicLink = $order->edit_route;
    }
}
