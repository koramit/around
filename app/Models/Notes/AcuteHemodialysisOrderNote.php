<?php

namespace App\Models\Notes;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Note;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Notes\AcuteHemodialysisOrderNote
 *
 * @property-read string $cancel_confirm_text
 * */
class AcuteHemodialysisOrderNote extends Note
{
    protected $table = 'notes';

    protected string $defaultChangeRequestClass = '\App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest';

    protected string $caseRecordClass = '\App\Models\Registries\AcuteHemodialysisCaseRecord';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
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
    public function getCasts(): array
    {
        return array_merge(parent::getCasts(), ['status' => AcuteHemodialysisOrderStatus::class]);
    }

    /** @alias $cancel_confirm_text */
    protected function cancelConfirmText(): Attribute
    {
        return Attribute::make(
            get: fn () => "Cancel {$this->meta['dialysis_type']} order on {$this->date_note->format('M j')}",
        );
    }

    public function scopeActiveStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getActiveStatusCodes());
    }

    public function scopeSlotOccupiedStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getSlotOccupiedStatusCodes());
    }

    public function genTitle(?string $dateNote = null): string
    {
        $dateNote = $dateNote
            ? now()->create($dateNote)
            : $this->date_note;

        return "Acute Hemodialysis Order : HN {$this->meta['hn']} {$this->meta['name']} : {$this->meta['dialysis_type']} {$dateNote->format('M j y')}";
    }
}
