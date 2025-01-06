<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\SearchHospital;
use Illuminate\Http\Request;

class HospitalController
{
    public function __invoke(Request $request, SearchHospital $action)
    {
        return $action($request->input('search'));
    }
}
