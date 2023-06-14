<?php

namespace App\Http\Controllers\Labs;

use App\Actions\RegistryTypeMainIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LabController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __construct(Request $request)
    {
        if (! $request->wantsJson()) {
            $this->middleware(['page-transition', 'locale', 'no-in-app-allow']);
        }
    }

    public function __invoke(Request $request)
    {
        $data = (new RegistryTypeMainIndexAction(
            registryType: 'labs',
            user: $request->user(),
            routeName: $request->route()->getName()
        ))();

        if ($request->wantsJson()) {
            return $data;
        }

        if ($data['redirect']) {
            return redirect()->route($data['redirect']);
        }

        $this->setFlash($data['flash']);

        return Inertia::render('Labs/MainIndex', [
            'routes' => [
                'kt-hla-typing' => route('labs.kt-hla-typing.reports.index'),
            ],
        ]);
    }
}
