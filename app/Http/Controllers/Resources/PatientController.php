<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\PatientShowAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new PatientShowAction())($request->input('hn'));
    }
}
