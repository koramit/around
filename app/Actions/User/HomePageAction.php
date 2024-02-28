<?php

namespace App\Actions\User;

use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;

class HomePageAction
{
    use AvatarLinkable, FlashDataGeneratable, HomePageSelectable;

    public function __invoke(mixed $user, string $routeName): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $flash = $this->getFlash(__('My Desk'), $user);
        $flash['action-menu'] = [$this->getSetHomePageActionMenu($routeName, $user->home_page)];

        return $flash;
    }
}
