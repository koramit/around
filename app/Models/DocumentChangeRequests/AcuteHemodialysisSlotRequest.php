<?php

namespace App\Models\DocumentChangeRequests;

use App\Actions\Procedures\AcuteHemodialysis\AcuteHemodialysisAction;
use App\Models\DocumentChangeRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AcuteHemodialysisSlotRequest extends DocumentChangeRequest
{
    protected $table = 'document_change_requests';

    protected static function booted(): void
    {
        parent::booted();

        static::addGlobalScope('acuteHemodialysisSlotRequestChangeableTypeScope', function (Builder $builder) {
            $builder->where('changeable_type', AcuteHemodialysisOrderNote::class);
        });
    }

    /** @alias $change_request_text */
    protected function changeRequestText(): Attribute
    {
        return Attribute::make(
            get: function () {
                /** @var AcuteHemodialysisOrderNote $changeable */
                $changeable = $this->changeable;
                $text = trim($changeable->meta['dialysis_type']).' / '.($changeable->meta['in_unit'] ? 'ห้อง Acute' : 'ward').' / ';
                $dateNoteStr = $changeable->date_note->format('Y-m-d');
                $changes = json_decode($this->attributes['changes'], true);
                if (isset($changes['swap'])) {
                    $swap = AcuteHemodialysisOrderNote::query()->find($changes['swap']);

                    return "แลก HN: {$swap->meta['hn']} {$swap->meta['name']} {$swap->date_note->format('M j')}".' / '.($changeable->meta['in_unit'] ? 'ห้อง Acute' : 'ward');
                }
                $dateLabel = (new AcuteHemodialysisAction)->getToday() === $changes['date_note'] ? 'วันนี้' : now()->create($changes['date_note'])->format('M j');
                if ($dateNoteStr === $changes['date_note']) {
                    $text = 'ขอ set '.$text.$dateLabel;
                } elseif ($dateNoteStr > $changes['date_note']) {
                    $text = 'ขอเลื่อนเข้า '.$text.'มา '.$dateLabel;
                } else {
                    $text = 'ขอเลื่อนออก '.$text.'ไป '.$dateLabel;
                }
                if ($changeable->meta['extra_slot']) {
                    $text .= ' / Extra slot';
                } elseif ($changeable->meta['covid_case'] ?? false) {
                    $text .= ' / COVID case';
                }

                return $text;
            },
        );
    }
}
