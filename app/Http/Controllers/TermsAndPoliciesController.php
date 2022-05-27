<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class TermsAndPoliciesController extends Controller
{
    public function __invoke()
    {
        Session::flash('page-title', __('Privacy Policies and Terms'));

        return Inertia::render('TermsAndPolicies');
    }
}
