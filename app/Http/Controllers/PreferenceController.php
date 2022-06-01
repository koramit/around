<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PreferenceController extends Controller
{
    public function show()
    {
        return Inertia::render('UserPreference');
    }
}
