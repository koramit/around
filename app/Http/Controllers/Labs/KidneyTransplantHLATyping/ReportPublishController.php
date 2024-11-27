<?php

namespace App\Http\Controllers\Labs\KidneyTransplantHLATyping;

use App\Actions\Labs\KidneyTransplantHLATyping\ReportPublishAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportPublishController extends Controller
{
    public function __invoke(string $hashedKey, Request $request)
    {
        $data = (new ReportPublishAction)($hashedKey, $request->all(), $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        if (isset($data['message'])) {
            session()->flash(key: 'message', value: $data['message']);
        }

        return redirect()->route('labs.kt-hla-typing.reports.index');
    }
}
