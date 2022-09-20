<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PageTransition
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
        $current = app('request')->route()->getName();
        session()->put('no-page-transition', session('no-page-transition-previous') === $current);
        session()->put('no-page-transition-previous', $current);

        return $next($request);
    }
}
