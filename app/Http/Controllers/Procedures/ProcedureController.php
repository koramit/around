<?php

namespace App\Http\Controllers\Procedures;

use App\Actions\Procedures\ProcedureIndexAction;
use App\Http\Controllers\Controller;
use App\Traits\AppLayoutSessionFlashable;
use App\Traits\HomePageSelectable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    use AppLayoutSessionFlashable, HomePageSelectable;

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $data = (new ProcedureIndexAction())($user);

        if ($request->wantsJson()) {
            return $data;
        }

        if ($data['redirect']) {
            return redirect()->route($data['redirect']);
        }

        $data['flash']['action-menu'] = [
            $this->getSetHomePageActionMenu($request->route()->getname(), $user->home_page),
        ];

        $this->setFlash($data['flash']);

        return Inertia::render('Procedures/MainIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index'),
            ],
        ]);
    }
}
