<?php

namespace App\Traits;

use App\Models\User;

trait HomePageSelectable
{
    protected function getSetHomePageActionMenu(string $routeName, User $user): ?array
    {
        return [
            'icon' => 'house',
            'type' => 'set-home-page-clicked',
            'action' => ['name' => $routeName, 'route' => route('preferences.update')],
            'label' => ($user->home_page === $routeName ? 'Already s' : 'S').'et as Home page',
            'can' => true,
        ];
    }
}
