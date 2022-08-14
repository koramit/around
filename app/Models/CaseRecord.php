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

    protected array $statuses = ['', 'draft', 'submitted', 'canceled', 'dismissed', 'archived', 'deleted'];

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

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statuses[$this->attributes['status']] ?? null,
            set: fn ($value) => array_search($value, $this->statuses) ?? null,
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->meta['title'] ?? 'placeholder',
        );
    }

    public function genTitle(): string
    {
        return 'placeholder';
    }
}
