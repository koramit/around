<?php

namespace App\Models\Resources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    public function scopeSearch($query, $search)
    {
        $iLike = config('database.iLike');
        $query->where('name', $iLike, '%'.$search.'%')
            ->orWhere('name_short', $iLike, '%'.$search.'%')
            ->orWhere('name_ref', $iLike, '%'.$search.'%')
            ->where('active', true);
    }
}
