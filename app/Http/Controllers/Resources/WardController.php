<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Resources\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function __invoke(Request $request)
    {
        $ilike = config('database.ilike');

        return Ward::query()
                    ->select('name')
                     ->where('name', $ilike, '%'.$request->input('search').'%')
                     ->orWhere('name_short', $ilike, '%'.$request->input('search').'%')
                     ->orWhere('name_ref', $ilike, '%'.$request->input('search').'%')
                     ->where('active', true)
                     ->get()
                     ->pluck('name');
    }
}
