<?php

namespace App\Actions\Clinics\PostKT;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;

class CaseIndexAction
{
    use AvatarLinkable, FlashDataGeneratable, HomePageSelectable;

    public function __invoke(array $filters, User|AvatarUser $user, string $routeName = 'home')
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }
    }
}
