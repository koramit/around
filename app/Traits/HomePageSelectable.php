<?php

namespace App\Traits;

trait HomePageSelectable
{
    protected function getSetHomePageActionMenu(string $routeName, string $userHomePage): ?array
    {
        if (str_starts_with($routeName, 'avatar.')) {
            $routeName = str_replace('avatar.', '', $routeName);
        }

        if ($userHomePage === $routeName) {
            return null;
        }

        return [
            'as' => 'button',
            'icon' => 'house',
            'name' => 'set-home-page-clicked',
            'config' => ['route_name' => $routeName, 'route' => route('preferences.update')],
            'label' => 'Set as Home page',
            'can' => true,
        ];
    }
}
