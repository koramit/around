<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\SlotAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = (new SlotAction)($request->all());

        // if want json return $data

        return $data;
    }
}
