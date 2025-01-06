<?php

namespace App\Http\Controllers\Clinics;

use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;

class ClinicController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow']);
        }
    }

    public function __invoke()
    {
        // @TODO init module registry

        return redirect()->route('clinics.post-kt.index');
    }
}
