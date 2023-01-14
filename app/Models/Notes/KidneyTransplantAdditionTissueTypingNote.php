<?php

namespace App\Models\Notes;

use App\Casts\KidneyTransplantHLATypingReportStatus;
use App\Models\Note;
use App\Models\Resources\NoteType;
use Illuminate\Database\Eloquent\Builder;

class KidneyTransplantAdditionTissueTypingNote extends Note
{
    protected $table = 'notes';

    protected string $caseRecordClass = '\App\Models\Registries\KidneyTransplantHLATypingCaseRecord';

    protected static function booted(): void
    {
        static::creating(function ($note) {
            $note->note_type_id = cache()->rememberForever(
                'note-type-id-kt_addition_tissue_typing_note',
                fn () => NoteType::query()->where('name', 'kt_addition_tissue_typing_note')->first()->id
            );
        });

        static::addGlobalScope('kidneyTransplantAdditionTissueTypingNoteTypeScope', function (Builder $builder) {
            $builder->where(
                'note_type_id',
                cache()->rememberForever(
                    'note-type-id-kt_addition_tissue_typing_note',
                    fn () => NoteType::query()->where('name', 'kt_addition_tissue_typing_note')->first()->id
                ));
        });
    }

    /**
     * Override.
     */
    public function getCasts(): array
    {
        return array_merge(parent::getCasts(), ['status' => KidneyTransplantHLATypingReportStatus::class]);
    }
}
