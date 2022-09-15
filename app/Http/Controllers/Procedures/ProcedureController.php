<?php

namespace App\Http\Controllers\Procedures;

use App\Actions\Procedures\ProcedureIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    use AppLayoutSessionFlashable;

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $data = (new ProcedureIndexAction())($user, $request->route()->getName());

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
