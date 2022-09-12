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
            'icon' => 'house',
            'type' => 'set-home-page-clicked',
            'action' => ['name' => $routeName, 'route' => route('preferences.update')],
            'label' => 'Set as Home page',
            'can' => true,
        ];
    }
}
