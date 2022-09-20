<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\TodaySlotRequestAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TodaySlotRequestController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new TodaySlotRequestAction)(hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return back()->with('message', $reply);
    }
}
