<?php

namespace App\Models\Notes;

use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;

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
}
