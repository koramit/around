<?php

namespace App\Http\Controllers\Labs\KidneyTransplantHLATyping;

use App\Actions\Labs\KidneyTransplantHLATyping\ReportDestroyAction;
use App\Actions\Labs\KidneyTransplantHLATyping\ReportEditAction;
use App\Actions\Labs\KidneyTransplantHLATyping\ReportIndexAction;
use App\Actions\Labs\KidneyTransplantHLATyping\ReportStoreAction;
use App\Actions\Labs\KidneyTransplantHLATyping\ReportUpdateAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['remember', 'page-transition', 'locale', 'no-in-app-allow'])->only(['index']);
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow'])->only(['edit']);
        }
    }

    public function index(Request $request)
    {
        $data = (new ReportIndexAction)(filters: $request->all(), user: $request->user(), routeName: $request->route()->getName());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Labs/KidneyTransplantHLATyping/ReportIndex', [...$data]);
    }

    public function store(Request $request)
    {
        $report = (new ReportStoreAction)(data: $request->all(), user: $request->user());

        if ($request->wantsJson()) {
            return $report;
        }

        return redirect()->route('labs.kt-hla-typing.reports.edit', $report['key']);
    }

    public function edit(string $hashedKey, Request $request)
    {
        $data = (new ReportEditAction)($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Labs/KidneyTransplantHLATyping/ReportEdit', [...$data]);
    }

    public function update(string $hashedKey, Request $request)
    {
        return (new ReportUpdateAction)($hashedKey, $request->all(), $request->user());
    }

    public function destroy(string $hashedKey, Request $request)
    {
        $data = (new ReportDestroyAction)($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        if (isset($data['message'])) {
            session()->flash(key: 'message', value: $data['message']);
        }

        return redirect()->route('labs.kt-hla-typing.reports.index');
    }
}
