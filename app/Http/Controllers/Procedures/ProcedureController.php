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
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ['icon' => 'comment-alt', 'label' => 'Feedback', 'route' => route('feedback'), 'can' => true],
            // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        ]);

        // check if there is one then redirect

        return Inertia::render('Procedures/MainIndex', [
            'routes' => [
                'acute-hemodialysis' => route('procedures.acute-hemodialysis.index'),
            ],
        ]);
    }
}
