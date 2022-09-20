<?php

namespace App\Http\Controllers\Procedures\AcuteHemodialysis;

use App\Actions\Procedures\AcuteHemodialysis\ScheduleIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['remember', 'page-transition', 'locale', 'no-in-app-allow']);
        }
    }

    public function __invoke(Request $request)
    {
        $data = (new ScheduleIndexAction)(data: $request->all(), user: $request->user(), routeName: $request->route()->getName());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        session()->put('acute-hemodialysis-last-index-section-route', $request->route()->getName());

        return Inertia::render('Procedures/AcuteHemodialysis/ScheduleIndex', [...$data]);
    }
}
