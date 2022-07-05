<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderRescheduleToTodayAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderRescheduleToTodayController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new OrderRescheduleToTodayAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $reply

        return back()->with('message', $reply);
    }
}
