<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|StreamedResponse
    {
        app()->setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
