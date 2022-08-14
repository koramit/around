<?php

namespace App\Notifications\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Notifications\MessagingApp;

class AlertOrderResubmit extends MessagingApp
{
    public function __construct(AcuteHemodialysisOrderNote $order)
    {
        $today = now()->tz(7)->format('Y-m-d');
        $dayLabel = $order->date_note->format('Y-m-d') === $today
            ? 'วันนี้'
            : 'พรุ่งนี้';
        $this->message = 'เคส acute '.$order->meta['name'].' '.$dayLabel." มีการเปลี่ยน order";
        $this->magicLink =route('procedures.acute-hemodialysis.orders.show', $order->hashed_key);
    }
}
