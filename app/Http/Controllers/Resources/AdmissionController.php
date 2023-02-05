<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\AdmissionShowAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string'],
        ]);

        return (new AdmissionShowAction())($request->input('key'));
    }
}
