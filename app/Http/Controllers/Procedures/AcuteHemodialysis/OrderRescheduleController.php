<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderRescheduleAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderRescheduleController extends Controller
{
    public function __invoke($hashedKey, Request $request)
    {
        $reply = (new OrderRescheduleAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $reply

        return back()->with('message', $reply);
    }
}
