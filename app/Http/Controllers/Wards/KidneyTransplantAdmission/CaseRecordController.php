<?php

namespace App\Http\Controllers\Wards\KidneyTransplantAdmission;

use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordDestroyAction;
use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordEditAction;
use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordIndexAction;
use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordStoreAction;
use App\Actions\Wards\KidneyTransplantAdmission\CaseRecordUpdateAction;
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

    public function index(Request $request)
    {
        $data = (new CaseRecordIndexAction)(filters: $request->all(), user: $request->user(), routeName: $request->route()->getName());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        session()->put('kidney-transplant-admission-last-index-section-route', $request->route()->getName());

        return Inertia::render('Wards/KidneyTransplantAdmission/CaseIndex', [...$data]);
    }

    public function store(Request $request)
    {
        $case = (new CaseRecordStoreAction)(data: $request->all(), user: $request->user());

        if ($request->wantsJson()) {
            return $case;
        }

        return redirect()->route('wards.kt-admission.edit', $case['key']);
    }

    public function edit(string $hashedKey, Request $request)
    {
        $data = (new CaseRecordEditAction)(hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Wards/KidneyTransplantAdmission/CaseEdit', [...$data]);
    }

    public function update(string $hashedKey, Request $request)
    {
        return (new CaseRecordUpdateAction)(data: $request->all(), hashedKey: $hashedKey, user: $request->user());
    }

    // destroy case
    public function destroy($hashedKey, Request $request)
    {
        $message = (new CaseRecordDestroyAction)(hashedKey: $hashedKey, user: $request->user());

        if ($request->wantsJson()) {
            return $message;
        }

        return redirect()->route('wards.kt-admission.index')->with('message', $message);
    }
}
