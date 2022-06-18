<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use App\Models\Resources\AttendingStaff;
use Illuminate\Http\Request;

class AttendingStaffController extends Controller
{
    public function __invoke(Request $request)
    {
        $like = config('database.default') === 'pgsql' ? 'ilike' : 'like';
        $staffs = AttendingStaff::select('name')
                     ->when($request->input('division_id') ?? null, function ($query, $divisionId) {
                         $query->where('division_id', $divisionId);
                     })
                     ->where('name', $like, '%'.$request->input('search').'%')
                     ->where('active', true)
                     ->get()
                     ->pluck('name');

        return $staffs;
    }
}
