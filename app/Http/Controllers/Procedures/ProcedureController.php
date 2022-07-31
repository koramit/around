<?php

namespace App\Http\Controllers\Procedures;

use App\Http\Controllers\Controller;
use App\Traits\HomePageSelectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    use HomePageSelectable;

    public function __invoke(Request $request)
    {
        Session::flash('page-title', 'Procedures');
        Session::flash('main-menu-links', [
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        ]);
        Session::flash('action-menu', [
            $this->getSetHomePageActionMenu($request->route()->getname(), $request->user()),
        ]);

        // check if there is one then redirect

        return Inertia::render('Procedures/MainIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index'),
            ],
        ]);
    }
}
