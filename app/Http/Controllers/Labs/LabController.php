<?php

namespace App\Http\Controllers\Labs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow']);
        }
    }

    public function __invoke()
    {
        return redirect()->route('labs.kt-hla-typing.reports.index');
    }
}
