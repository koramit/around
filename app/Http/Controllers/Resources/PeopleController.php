<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Resources\Person;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function __invoke(Request $request)
    {
        $ilike = config('database.ilike');

        return Person::query()
            ->select('name')
            ->when($request->input('position') ?? null, function ($query, $position) {
                $query->where('position', $position);
            })
            ->when($request->input('division_id') ?? null, function ($query, $division_id) {
                $query->where('division_id', $division_id);
            })
            ->where('name', $ilike, '%'.$request->input('search').'%')
            ->where('active', true)
            ->get()
            ->pluck('name');
    }
}
