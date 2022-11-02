<?php

namespace App\Models\Notes;

use App\Casts\KidneyTransplantHLATypingReportStatus;
use App\Models\Note;
use App\Models\Resources\NoteType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class KidneyTransplantHLATypingReportNote extends Note
{
    protected $table = 'notes';

    protected string $caseRecordClass = '\App\Models\Registries\KidneyTransplantHLATypingCaseRecord';

    protected static function booted(): void
    {
        static::creating(function ($note) {
            $note->note_type_id = cache()->rememberForever(
                'note-type-id-kt_hla_typing_report',
                fn () => NoteType::query()->where('name', 'kt_hla_typing_report')->first()->id
            );
        });

        static::addGlobalScope('kidneyTransplantHLATypingReportNoteTypeScope', function (Builder $builder) {
            $builder->where(
                'note_type_id',
                cache()->rememberForever(
                    'note-type-id-kt_hla_typing_report',
                    fn () => NoteType::query()->where('name', 'kt_hla_typing_report')->first()->id
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

    /* @alias $edit_route */
    protected function editRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('labs.kt-hla-typing.reports.edit', $this->hashed_key),
        );
    }

    public function request(): Attribute
    {
        return Attribute::make(
            get: function () {
                $request = $this->meta['request_hla'] ? 'HLA ':null;
                $request .= ($this->meta['request_cxm'] ? 'CXM ':null);
                $request .= ($this->meta['donor_hn'] ? 'with LD ':null);

                return trim($request);
            }
        );
    }

    public function genTitle(?string $dateNote = null): string
    {
        return "HN {$this->patient->hn} {$this->patient->full_name} : $this->request Report {$this->date_note->format('M j Y')}";
    }
}
