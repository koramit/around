<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\IdleCaseAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IdleCaseController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new IdleCaseAction)($request->input('search'));
    }
}
