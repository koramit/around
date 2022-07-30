<?php

namespace App\Http\Controllers;

use App\Actions\User\ProfileUpdateAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    public function show()
    {
        return Inertia::render('UserPreference');
    }

    public function update(Request $request)
    {
        return (new ProfileUpdateAction)($request->all(), $request->user());
    }
}
