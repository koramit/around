<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\DialysisSessionDestroyAction;
use App\Actions\Procedures\AcuteHemodialysis\DialysisSessionStoreAction;
use App\Actions\Procedures\AcuteHemodialysis\DialysisSessionUpdateAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DialysisSessionController extends Controller
{
    public function store(string $hashedKey, Request $request)
    {
        $reply = (new DialysisSessionStoreAction)($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return back()->with('message', $reply);
    }

    public function destroy(string $hashedKey, Request $request)
    {
        $reply = (new DialysisSessionDestroyAction)($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return back()->with('message', $reply);
    }

    public function update(string $hashedKey, Request $request)
    {
        $reply = (new DialysisSessionUpdateAction)($hashedKey, $request->all(), $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return back()->with('message', $reply);
    }
}
