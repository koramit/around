<?php

namespace App\Actions\Labs;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;
use App\Traits\RegistryGroupRouteQueryable;

class LabIndexAction
{
    use AvatarLinkable, RegistryGroupRouteQueryable, FlashDataGeneratable, HomePageSelectable;

    public function __invoke(User|AvatarUser $user, string $routeName)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if ($user->registry_names->count() === 0) {
            abort(403);
        }

        $labs = $this->getRoutesByRegistryTypeAndUser(registryType: 'labs', user: $user);
        if ($labs->count() === 0) {
            abort(403);
        }

        $redirectTo = null;
        if ($labs->count() === 1) {
            $redirectTo = $labs[0]['route'];
        }

        $flash = $this->getFlash(__('Labs'), $user);
        $flash['action-menu'] = [$this->getSetHomePageActionMenu($routeName, $user->home_page)];

        return [
            'redirect' => $redirectTo,
            'flash' => $flash,
        ];
    }
}
