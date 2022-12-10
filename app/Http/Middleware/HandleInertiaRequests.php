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
     * @param  Request  $request
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
     * @param  Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'routeMyDesk' => fn () => route('home'),
            'routeHome' => fn () => route(($request->user()?->home_page) ?? 'home'),
            'routePreferences' => fn () => route('preferences'),
            'routeManageUser' => fn () => route('users.index'),
            'routeSupport' => fn () => [route('support-tickets.index'), route('feedback.index')],
            'routeLogout' => fn () => route('logout'),
            'flash' => [
                'title' => fn () => $request->session()->pull('page-title', 'MISSING'),
                'hn' => fn () => $request->session()->pull('hn'),
                'mainMenuLinks' => fn () => $request->session()->pull('main-menu-links', []),
                'actionMenu' => fn () => $request->session()->pull('action-menu', []),
                'breadcrumbs' => fn () => $request->session()->pull('breadcrumbs', []),
                'navs' => fn () => $request->session()->pull('navs', []),
                'message' => fn () => $request->session()->pull('message'),
            ],
            'noPageTransition' => fn () => $request->session()->get('no-page-transition'),
            'user' => fn () => $request->user()
                ? [
                    'name' => $request->user()->name,
                    'preferences' => $request->session()->get('preferences', [
                        'appearance' => [
                            'zenMode' => $request->user()->preferences['zen_mode'],
                            'fontScaleIndex' => $request->user()->preferences['font_scale_index'],
                        ],
                        'discussion_mode' => 'timeline',
                    ]),
                    'can' => [
                        'manage_user' => $request->user()->can('authorize_user'),
                        'config_preferences' => $request->user()->can('config_preferences'),
                        'get_support' => false, // $request->user()->can('get_support'),
                    ],
                ] : null,
            'event' => [
                'fire' => null,
                'name' => '',
                'payload' => null,
            ],
            'form' => [
                'state' => null,
                'error' => null,
            ],
        ]);
    }
}
