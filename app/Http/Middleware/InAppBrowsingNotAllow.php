<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class InAppBrowsingNotAllow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $agent = new Agent();

        $agentName = trim($request->header('User-Agent').' '.($agent->isRobot() ? $agent->robot() : ''));

        $inAppBrowsing =  preg_match("/Line\//i", $agentName) || preg_match('/facebook/i', $agentName);

        if (! $inAppBrowsing) {
            return $next($request);
        }

        $token = Str::random(32);
        cache()->put(
            Key:'in-app-browsing-redirect-token-'.$token,
            value: $request->fullUrl(),
            ttl: now()->addMinutes(5)
        );

        return redirect()->route('in-app-browsing-redirect', $token);
    }
}
