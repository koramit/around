<?php

namespace App\Http\Controllers;

use App\Traits\HomePageSelectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class HomeController extends Controller
{
    use HomePageSelectable;

    public function __invoke(Request $request)
    {
        Session::flash('page-title', __('My Desk'));
        Session::flash('main-menu-links', collect([
            ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => true],
            ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => true],
            ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => true],
            ['icon' => 'comment-alt', 'label' => 'Feedback', 'route' => route('feedback'), 'can' => true],
        ])->filter(fn ($link) => $link['can']));
        Session::flash('action-menu', [
            $this->getSetHomePageActionMenu($request->route()->getname(), $request->user()),
        ]);
        // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        // ['icon' => 'graduation-cap', 'label' => 'Club Nephro', 'route' => 'procedures', 'can' => true],
        // ['icon' => 'box', 'label' => 'Code Drive', 'route' => 'procedures', 'can' => true],

        return Inertia::render('MyDesk');
    }
}
