<?php

namespace App\Http\Controllers\Clinics\PostKT;

use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CaseRecordController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['remember', 'page-transition', 'locale', 'no-in-app-allow'])->only(['index']);
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow'])->only(['edit']);
        }
    }

    public function index()
    {
        return 'POST KT cases index';
    }

    public function edit()
    {
        return Inertia::render('Clinics/PostKT/Edit', []);
    }
}
