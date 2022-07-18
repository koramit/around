<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\SlotAvailableAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlotAvailableController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = (new SlotAvailableAction)($request->all());

        // if request want json return $data

        return $data;
    }
}
