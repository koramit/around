<?php

namespace App\Http\Controllers;

use App\Actions\User\PreferencesUpdateAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    public function show()
    {
        return Inertia::render('User/PreferencePage');
    }

    public function update(Request $request)
    {
        return (new PreferencesUpdateAction)($request->all(), $request->user());
    }
}
