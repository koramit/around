<?php

namespace App\Notifications\Procedures\AcuteHemodialysis;

use App\Contracts\MessagingApp;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class AlertSlotRequest extends Notification implements MessagingApp
{
    use SocialAppMessagable;

    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $this->message = "มีคำร้องขอใช้งาน slot เคส {$order->meta['name']} รออนุมัติ";
        $this->magicLink = route('procedures.acute-hemodialysis.slot-requests');
    }
}
