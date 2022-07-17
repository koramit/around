<?php

namespace App\Traits\AcuteHemodialysis;

use App\Models\DocumentChangeRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;

trait SlotRequestable
{
    protected function getChangeRequestText(DocumentChangeRequest $request): string
    {
        /** @var AcuteHemodialysisOrderNote $changeable */
        $changeable = $request->changeable;
        $text = trim($changeable->meta['dialysis_type']).' / '.($changeable->meta['in_unit'] ? 'ห้อง Acute' : 'ward').' / ';
        $dateNoteStr = $changeable->date_note->format('Y-m-d');
//        $dateLabel = (new AcuteHemodialysisAction)->getToday() === $request->changes['date_note'] ? 'วันนี้' : now()->create($request->changes['date_note'])->format('M j');
        $dateLabel = 'วันนี้';
        if ($dateNoteStr === $request->changes['date_note']) {
            $text = 'ขอ set '.$text.$dateLabel;
        } else {
            $text = 'ขอเลื่อนเข้า '.$text.'มา'.$dateLabel;
        }

        return $text;
    }
}
