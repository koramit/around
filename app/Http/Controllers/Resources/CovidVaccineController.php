<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\CovidVaccineAction;
use App\Contracts\PatientAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CovidVaccineController extends Controller
{
    public function __invoke(PatientAPI $api, Request $request)
    {
        return (new CovidVaccineAction)(data: $request->all(), api: $api);
    }
}
