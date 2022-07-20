<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\CovidLabAction;
use App\Contracts\PatientAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CovidLabController extends Controller
{
    public function __invoke(PatientAPI $api, Request $request)
    {
        return (new CovidLabAction)(data: $request->all(), api: $api);
    }
}
