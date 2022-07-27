<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Http\Controllers\Controller;

class LastIndexSectionController extends Controller
{
    public function __invoke()
    {
        if (! $lastRoute = session()->pull('acute-hemodialysis-last-index-section-route')) {
            return redirect()->route('procedures.acute-hemodialysis.index');
        }

        return redirect()->route($lastRoute);
    }
}
