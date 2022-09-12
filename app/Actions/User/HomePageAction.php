<?php

namespace App\Actions\User;

use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;

class HomePageAction
{
    use FlashDataGeneratable, HomePageSelectable, AvatarLinkable;

    public function __invoke(mixed $user, string $routeName): array
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) {
            return $link;
        }

        $flash = $this->getFlash(__('My Desk'), $user);
        $flash['action-menu'] = [$this->getSetHomePageActionMenu($routeName, $user->home_page)];

        return $flash;
        // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        // ['icon' => 'graduation-cap', 'label' => 'Club Nephro', 'route' => 'procedures', 'can' => true],
        // ['icon' => 'box', 'label' => 'Code Drive', 'route' => 'procedures', 'can' => true],
    }
}
