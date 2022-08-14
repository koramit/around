<?php

namespace App\Http\Controllers;

class InAppBrowsingRedirectController extends Controller
{
    public function __invoke($token)
    {
        $to = cache()->pull("in-app-browsing-redirect-app-browsing-redirect-$token");
        if (! $to) {
            abort(404);
        }

        return redirect($to);
    }
}
