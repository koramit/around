<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\PatientRecentlyAdmissionAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientRecentlyAdmissionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string'],
        ]);

        return (new PatientRecentlyAdmissionAction)($request->input('key'));
    }
}
