<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\SlotAvailableDatesAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SlotAvailableDatesController extends Controller
{
    public function __invoke(Request $request)
    {
        return (new SlotAvailableDatesAction)($request->all(), $request->user());
    }
}
