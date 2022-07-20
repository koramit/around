<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'routeHome' => fn () => route('home'),
            'routePreferences' => fn () => route('preferences'),
            'routeLogout' => fn () => route('logout'),
            'routeCovidLab' => fn () => route('resources.api.covid-lab'),
            'routeCovidVaccine' => fn () => route('resources.api.covid-vaccine'),
            'flash' => [
                'title' => fn () => $request->session()->pull('page-title', 'MISSING'),
                'hn' => fn () => $request->session()->pull('hn'),
                'cid' => fn () => $request->session()->pull('cid'),
                'mainMenuLinks' => fn () => $request->session()->pull('main-menu-links', []),
                'actionMenu' => fn () => $request->session()->pull('action-menu', []),
                'breadcrumbs' => fn () => $request->session()->pull('breadcrumbs', []),
                'navs' => fn () => $request->session()->pull('navs', []),
                'message' => fn () => $request->session()->pull('message'),
            ],
            'shouldTransitionPage' => fn () => $request->session()->get('should-transition-page'),
            'user' => fn () => $request->user()
                ? [
                    'name' => $request->user()->name,
                    'configs' => $request->session()->get('configs', [
                        'appearance' => ['zenMode' => false, 'fontScaleIndex' => 3],
                    ]),
                ] : null,
            'event' => [
                'fire' => null,
                'name' => '',
                'payload' => null,
            ],
        ]);
    }
}
