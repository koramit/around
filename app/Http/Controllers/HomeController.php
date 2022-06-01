<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        Session::flash('page-title', __('Home'));
        Session::flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures'), 'can' => true],
        ])->filter(fn ($link) => $link['can']));
        Session::flash('action-menu', [
            ['icon' => 'save', 'label' => 'บันทึก', 'action' => 'save', 'can' => true],
        ]);
        // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        // ['icon' => 'graduation-cap', 'label' => 'Club Nephro', 'route' => 'procedures', 'can' => true],
        // ['icon' => 'box', 'label' => 'Code Drive', 'route' => 'procedures', 'can' => true],

        return Inertia::render('HomePage');
    }
}
