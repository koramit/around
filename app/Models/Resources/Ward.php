<?php

namespace App\Models\Resources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        $ilike = config('database.ilike');
        $query->where('name', $ilike, '%'.$search.'%')
            ->orWhere('name_short', $ilike, '%'.$search.'%')
            ->orWhere('name_ref', $ilike, '%'.$search.'%')
            ->where('active', true);
    }
}
