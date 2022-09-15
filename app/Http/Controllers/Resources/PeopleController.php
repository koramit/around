<?php

namespace App\Http\Controllers\Resources;

use App\Actions\Resources\PersonSearchAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new PersonSearchAction())($request->all());
    }
}
