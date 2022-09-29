<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PageTransition
{
    public function handle(Request $request, Closure $next)
    {
        $current = $request->route()->getName();
        session()->put('no-page-transition', session('no-page-transition-previous') === $current);
        session()->put('no-page-transition-previous', $current);

        return $next($request);
    }
}
