<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\WardSearchAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WardController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new WardSearchAction())($request->input('search'));
    }
}
