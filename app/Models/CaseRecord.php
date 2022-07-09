<?php

namespace App\Models;

use App\Models\Resources\Patient;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseRecord extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'form' => AsArrayObject::class,
        'meta' => AsArrayObject::class,
    ];

    protected $statuses = ['', 'draft', 'submitted', 'canceled', 'dismissed', 'archived'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function actionLogs()
    {
        return $this->morphMany(ResourceActionLog::class, 'loggable');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->statuses[$this->attributes['status']] ?? null,
            set: fn ($value) => array_search($value, $this->statuses) ?? null,
        );
    }
}
