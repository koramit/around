<?php

namespace App\Http\Controllers\Labs\KidneyTransplantHLATyping;

use App\Actions\Labs\KidneyTransplantHLATyping\ReportCancelAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportCancelController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $reply = (new ReportCancelAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $reply;
        }

        return redirect()->route('labs.kt-hla-typing.reports.index');
    }
}
