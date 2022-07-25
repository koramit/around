<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\OrderSwapAction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class OrderSwapController extends Controller
{
    /**
     * @throws Exception
     */
    public function __invoke($hashedKey, Request $request)
    {
        $reply = (new OrderSwapAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        // if request want json return $reply

        return back()->with('message', $reply);
    }
}
