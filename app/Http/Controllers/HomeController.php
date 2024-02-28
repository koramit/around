<?php

namespace App\Http\Controllers;

use App\Actions\User\HomePageAction;
use App\Traits\AppLayoutSessionFlashable;
use App\Traits\HomePageSelectable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    use AppLayoutSessionFlashable, HomePageSelectable;

    public function __invoke(Request $request)
    {
        $data = (new HomePageAction())($request->user(), $request->route()->getName());
        $this->setFlash($data);

        if ($request->wantsJson()) {
            return $data;
        }

        return Inertia::render('User/MyDesk');
    }
}
