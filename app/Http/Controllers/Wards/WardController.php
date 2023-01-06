<?php

namespace App\Http\Controllers\Wards;

use App\Actions\MainRegistryTypeIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WardController extends Controller
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
        $data = (new MainRegistryTypeIndexAction(
            registryType: 'wards',
            user:  $request->user(),
            routeName: $request->route()->getName()
        ))();

        if ($request->wantsJson()) {
            return $data;
        }

        if ($data['redirect']) {
            return redirect()->route($data['redirect']);
        }

        $this->setFlash($data['flash']);

        return Inertia::render('Wards/MainIndex', [
            'routes' => [
                'kt-admission' => route('wards.kt-admission.index'),
            ],
        ]);
    }
}
