<?php

namespace App\Http\Controllers;

use App\Traits\InAppBrowsingAware;
use Illuminate\Http\Request;

class InAppBrowsingRedirectController extends Controller
{
    use InAppBrowsingAware;

    public function __invoke($token, Request $request)
    {
        if ($this->inAppBrowsing($request)) {
            return 'please open this link in web browser';
        }

        $to = cache()->pull("in-app-browsing-redirect-token-$token");
        if (! $to) {
            abort(404);
        }

        return redirect($to);
    }
}
