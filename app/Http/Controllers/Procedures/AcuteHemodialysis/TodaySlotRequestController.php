<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderTodaySlotRequestAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderTodaySlotRequestController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new OrderTodaySlotRequestAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if want json return $reply

        return back()->with('message', $reply);
    }
}
