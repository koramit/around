<?php

namespace App\Http\Controllers\Clinics\PostKT;

use App\Actions\Clinics\PostKT\AnnualUpdateAction;
use App\Actions\Clinics\PostKT\CaseEditAction;
use App\Actions\Clinics\PostKT\CaseStoreAction;
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
        $configs = [
            'can' => ['create' => true],
            'routes' => [
                'store' => route('clinics.post-kt.store'),
                'admissionsShow' => route('resources.api.admissions.show')
            ],
        ];

        return Inertia::render('Clinics/PostKT/Index', [
            'configs' => $configs,
        ]);
    }

    public function store(Request $request, CaseStoreAction $action)
    {
        $case = $action($request->all(), $request->user());

        if ($request->wantsJson()) {
            return $case;
        }

        return redirect()->route('clinics.post-kt.edit', $case['key']);
    }

    public function edit(string $hashedKey, Request $request, CaseEditAction $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);
        unset($data['flash']);

        return Inertia::render('Clinics/PostKT/Edit', [...$data]);
    }

    public function annualUpdate(string $hashedKey, Request $request, AnnualUpdateAction $action)
    {
        $data = $action($hashedKey, $request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        return redirect()->route('clinics.post-kt.edit', $hashedKey);
    }
}
