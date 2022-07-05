<?php

namespace App\Models\Notes;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\ArrayObject;

class AcuteHemodialysisOrderNote extends Note
{
    protected $table = 'notes';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($note) {
            $note->note_type_id = config('notes.acute_hd_order');
        });

        static::addGlobalScope('acuteHemodialysisOrderNoteTypeScope', function (Builder $builder) {
            $builder->where('note_type_id', config('notes.acute_hd_order'));
        });
    }

    /**
     * Override.
     */
    public function getCasts()
    {
        return array_merge(parent::getCasts(), ['status' => AcuteHemodialysisOrderStatus::class]);
    }

    public function scopeActiveStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getActiveStatusCodes());
    }

    public function getChangRequestText(ArrayObject $changes): string
    {
        $text = trim($this->meta['dialysis_type']).' / '.($this->meta['in_unit'] ? 'ไตเทียม' : 'ward').' / ';
        $dateNoteStr = $this->date_note->format('Y-m-d');
        if ($dateNoteStr === $changes['date_note']) {
            $text = 'ขอ set '.$text.'วันนี้';
        } else {
            $text = 'ขอย้าย '.$text.'มาวันนี้';
        }

        return $text;
    }
}
