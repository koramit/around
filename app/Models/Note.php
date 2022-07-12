<?php

namespace App\Models;

use App\Models\Resources\AttendingStaff;
use App\Models\Resources\Patient;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\Note
 *
 * @property-read string $hashed_key
 * @property-read string place_name
 */
class Note extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'form' => AsArrayObject::class,
        'meta' => AsArrayObject::class,
        'date_note' => 'date',
    ];

    public function patient(): HasOneThrough
    {
        return $this->hasOneThrough(
            Patient::class, // target
            CaseRecord::class, // via
            'id', // selected key on the via table...
            'id', // selected key on the target table...
            'case_record_id', // link key this table => via table...
            'patient_id' // link key via table => target table...
        );
    }

    public function caseRecord(): BelongsTo
    {
        return $this->belongsTo(CaseRecord::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendingStaff(): BelongsTo
    {
        return $this->belongsTo(AttendingStaff::class);
    }

    public function changeRequests(): MorphMany
    {
        return $this->morphMany(DocumentChangeRequest::class, 'changeable');
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    public function scopeWithAuthorUsername($query)
    {
        $query->addSelect([
            'author_username' => User::select('name')
                    ->whereColumn('id', 'notes.author_id')
                    ->limit(1)
                    ->latest(),
        ]);
    }

    public function scopeWithPlaceName($query, $className)
    {
        $query->addSelect([
            'place_name' => $className::select('name')
                    ->whereColumn('id', 'notes.place_id')
                    ->limit(1)
                    ->latest(),
        ]);
    }
}
