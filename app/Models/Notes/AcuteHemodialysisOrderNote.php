<?php

namespace App\Models\Notes;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Events\Procedures\AcuteHemodialysis\AcuteHemodialysisOrderNoteUpdating;
use App\Models\Note;
use App\Models\Resources\NoteType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Notes\AcuteHemodialysisOrderNote
 *
 * @property-read string $cancel_confirm_text
 * @property-read string $edit_route
 * @property-read string $view_route
 * @property-read string $discussion_route
 * @property-read bool $on_ventilator
 * */
class AcuteHemodialysisOrderNote extends Note
{
    protected $table = 'notes';

    protected string $defaultChangeRequestClass = '\App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest';

    protected string $caseRecordClass = '\App\Models\Registries\AcuteHemodialysisCaseRecord';

    protected $dispatchesEvents = [
        'updating' => AcuteHemodialysisOrderNoteUpdating::class,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($note) {
            $note->note_type_id = cache()->rememberForever(
                'note-type-id-acute_hd_order',
                fn () => NoteType::query()->where('name', 'acute_hd_order')->first()->id
            );
        });

        static::addGlobalScope('acuteHemodialysisOrderNoteTypeScope', function (Builder $builder) {
            $builder->where(
                'note_type_id',
                cache()->rememberForever(
                    'note-type-id-acute_hd_order',
                    fn () => NoteType::query()->where('name', 'acute_hd_order')->first()->id
                ));
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

    public function scopeDialysisDate($query, $dateRef)
    {
        if (gettype($dateRef) === 'string') {
            if (config('database.default') === 'sqlite') {
                $dateRef = $dateRef.' 00:00:00';
            }
        }

        $query->where('date_note', $dateRef);
    }

    public function scopeDialysisTypeLike($query, $type)
    {
        $query->where('meta->dialysis_type', config('database.ilike'), "%$type%");
    }

    public function scopeActiveStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getActiveStatusCodes());
    }

    public function scopeSlotOccupiedStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getSlotOccupiedStatusCodes());
    }

    public function scopePerformedStatuses($query)
    {
        $query->whereIn('status', (new AcuteHemodialysisOrderStatus)->getPerformedStatusCodes());
    }

    public function genTitle(?string $dateNote = null): string
    {
        $dateNote = $dateNote
            ? now()->create($dateNote)
            : $this->date_note;

        return "HN {$this->meta['hn']} {$this->patient->full_name} : Acute {$this->meta['dialysis_type']} {$dateNote->format('M j y')}";
    }

    /** @alias $edit_route */
    protected function editRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('procedures.acute-hemodialysis.orders.edit', $this->hashed_key),
        );
    }

    /** @alias $view_route */
    protected function viewRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('procedures.acute-hemodialysis.orders.show', $this->hashed_key),
        );
    }

    /** @alias $discussion_route */
    protected function discussionRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route('procedures.acute-hemodialysis.orders.show', $this->hashed_key).'#discussion',
        );
    }

    /** @alias $on_ventilator */
    protected function onVentilator(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->form['oxygen_support'] === 'Ventilator',
        );
    }
}
