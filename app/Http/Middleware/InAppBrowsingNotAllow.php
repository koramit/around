<?php

namespace App\Http\Middleware;

use App\Traits\InAppBrowsingAware;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class InAppBrowsingNotAllow
{
    use InAppBrowsingAware;

    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! $this->inAppBrowsing($request)) {
            return $next($request);
        }

        $token = Str::random(32);
        cache()->put(
            key:'in-app-browsing-redirect-token-'.$token,
            value: $request->fullUrl(),
            ttl: now()->addMinutes(5)
        );

        return redirect()->route('in-app-browsing-redirect', $token);
    }
}
