<?php

namespace App\Models;

use App\Models\Resources\Patient;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * THIS CAN BE REPLACE WITH QUERY ON STATUS.
     *
     * @todo use note status instead of this
     */
    public function latestAcuteOrder()
    {
        return $this->hasOne(Note::class)->ofMany([
            'date_note' => 'max',
        ], function ($query) {
            $query->where('note_type_id', config('notes.acute_hd_order'));
        });
    }
}
