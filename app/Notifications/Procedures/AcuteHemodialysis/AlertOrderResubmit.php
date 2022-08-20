<?php

namespace App\Notifications\Procedures\AcuteHemodialysis;

use App\Contracts\MessagingApp;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\SocialAppMessagable;
use Illuminate\Notifications\Notification;

class AlertOrderResubmit extends Notification implements MessagingApp
{
    use SocialAppMessagable;

    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $today = now()->tz(7)->format('Y-m-d');
        $dayLabel = $order->date_note->format('Y-m-d') === $today
            ? 'วันนี้'
            : 'พรุ่งนี้';
        $this->message = 'เคส acute '.$order->meta['name'].' '.$dayLabel.' มีการเปลี่ยน order';
        $this->magicLink = route('procedures.acute-hemodialysis.orders.show', $order->hashed_key);
    }
}
