<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        app()->setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
