<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Resources\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function __invoke(Request $request)
    {
        $like = config('database.default') === 'pgsql' ? 'ilike' : 'like';
        $wards = Ward::query()
                    ->select('name')
                     ->where('name', $like, '%'.$request->input('search').'%')
                     ->orWhere('name_short', $like, '%'.$request->input('search').'%')
                     ->orWhere('name_ref', $like, '%'.$request->input('search').'%')
                     ->where('active', true)
                     ->get()
                     ->pluck('name');

        return $wards;
    }
}
