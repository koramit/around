<?php

namespace App\Http\Controllers\Procedures;

use App\Actions\MainRegistryTypeIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcedureController extends Controller
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
            registryType: 'procedures',
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

        return Inertia::render('Procedures/MainIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index'),
            ],
        ]);
    }
}
