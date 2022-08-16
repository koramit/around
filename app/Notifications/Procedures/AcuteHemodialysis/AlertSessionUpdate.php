<?php

namespace App\Notifications\Procedures\AcuteHemodialysis;

use App\Contracts\MessagingApp;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class AlertSessionUpdate extends Notification implements MessagingApp
{
    use SocialAppMessagable;

    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $this->message = "เคส {$order->meta['name']} session $order->status";
        $this->magicLink = $order->view_route;
    }
}
