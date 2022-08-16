<?php

namespace App\Models;

use App\Events\DocumentChangeRequestCreated;
use App\Events\DocumentChangeRequestUpdating;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\DocumentChangeRequest
 *
 * @property-read string $hashed_key
 * @property-read string $change_request_text
 * @property-read string $requester_name
 */
class DocumentChangeRequest extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'changes' => AsArrayObject::class,
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'disapproved_at' => 'datetime',
    ];

    protected array $statuses = ['', 'pending', 'approved', 'disapproved', 'canceled', 'expired'];

    protected $dispatchesEvents = [
        'created' => DocumentChangeRequestCreated::class,
        'updating' => DocumentChangeRequestUpdating::class,
    ];

    public $timestamps = false;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($request) {
            $request->submitted_at = now();
        });
    }

    public function changeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    public function scopeWithRequesterName($query)
    {
        $query->addSelect([
            'requester_name' => User::select('full_name')
                ->whereColumn('id', 'document_change_requests.requester_id')
                ->limit(1)
                ->latest(),
        ]);
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statuses[$this->attributes['status']] ?? null,
            set: fn ($value) => array_search($value, $this->statuses) ?? null,
        );
    }
}
