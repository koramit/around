<?php

namespace App\Http\Controllers\Procedures;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProcedureController extends Controller
{
    public function __invoke()
    {
        Session::flash('page-title', 'Procedures');
        Session::flash('main-menu-links', [
            ['icon' => 'patient', 'label' => 'Patients', 'route' => 'patients', 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => 'clinics', 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => 'procedures', 'can' => true],
            ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => 'kidney-club', 'can' => true],
        ]);

        // check if there is one then redirect

        return Inertia::render('Procedures/ProcedureIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index')
            ]
        ]);
    }
}
