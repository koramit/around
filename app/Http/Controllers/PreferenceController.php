<?php

namespace App\Http\Controllers;

use App\Actions\User\PreferencesShowAction;
use App\Actions\User\PreferencesUpdateAction;
use App\Traits\AppLayoutSessionFlashable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PreferenceController extends Controller
{
    use AppLayoutSessionFlashable;

    public function show(Request $request)
    {
        $data = (new PreferencesShowAction())($request->user());

        if ($request->wantsJson()) {
            return $data;
        }

        $this->setFlash($data['flash']);

        return Inertia::render('User/PreferencePage')->with([...$data['props']]);
    }

    public function update(Request $request)
    {
        return (new PreferencesUpdateAction)($request->all(), $request->user());
    }
}
