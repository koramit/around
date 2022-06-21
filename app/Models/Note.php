<?php

namespace App\Models;

use App\Casts\NoteStatus;
use App\Models\Resources\Patient;
use App\Traits\PKHashable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory, PKHashable;

    protected $guarded = [];

    protected $casts = [
        'form' => AsArrayObject::class,
        'meta' => AsArrayObject::class,
        'date_note' => 'date',
        'status' => NoteStatus::class,
    ];

    public function patient()
    {
        return $this->hasOneThrough(
            Patient::class, // target
            CaseRecord::class, // via
            'id', // selected key on the via table...
            'id', // selected key on the target table...
            'case_record_id', // link key this table => via table...
            'patient_id', // link key via table => target table...
        );
    }

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class);
    }

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function scopeWithAuthorUsername($query)
    {
        $query->addSelect([
            'author_username' => User::select('name')
                    ->whereColumn('id', 'notes.user_id')
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
