<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

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
