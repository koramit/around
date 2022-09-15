<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\CovidVaccineAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CovidVaccineController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new CovidVaccineAction)(data: $request->all());
    }
}
