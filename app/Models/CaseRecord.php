<?php

namespace App\Models;

use App\Models\Resources\Patient;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/** @property-read string $hashed_key */
class CaseRecord extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'form' => AsArrayObject::class,
        'meta' => AsArrayObject::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function actionLogs(): MorphMany
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['title'] ?? 'placeholder',
        );
    }

    protected function creator(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->actionLogs()->where('action', 1)->first()?->actor,
        );
    }

    public function genTitle(): string
    {
        return 'placeholder';
    }

    public function scopeMetaSearchTerms($query, $search)
    {
        $ilike = config('database.ilike');
        $query->when($search ?? null, function ($query, $search) use ($ilike) {
            $query->where('meta->name', $ilike, $search.'%')
                ->orWhere('meta->hn', $ilike, $search.'%');
        });
    }
}
