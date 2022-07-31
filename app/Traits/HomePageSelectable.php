<?php

namespace App\Traits;

use App\Models\User;

trait HomePageSelectable
{
    protected function getSetHomePageActionMenu(string $routeName, User $user): ?array
    {
        if ($user->home_page === $routeName) {
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
