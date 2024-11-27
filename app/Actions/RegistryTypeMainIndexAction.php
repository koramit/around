<?php

namespace App\Actions;

use App\Extensions\Auth\AvatarUser;
use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;
use App\Traits\RegistryGroupRouteQueryable;

class RegistryTypeMainIndexAction
{
    use AvatarLinkable, FlashDataGeneratable, HomePageSelectable, RegistryGroupRouteQueryable;

    public function __construct(
        protected string $registryType,
        protected User|AvatarUser $user,
        protected string $routeName,
    ) {}

    public function __invoke()
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if ($this->user->registry_names->count() === 0) {
            abort(403);
        }

        $registries = $this->getRoutesByRegistryTypeAndUser(registryType: $this->registryType, user: $this->user);
        if ($registries->count() === 0) {
            abort(403);
        }

        $redirectTo = null;
        if ($registries->count() === 1) {
            $redirectTo = $registries[0]['route'];
        }

        $flash = $this->getFlash(__(ucfirst($this->registryType)), $this->user);
        $flash['action-menu'] = [$this->getSetHomePageActionMenu($this->routeName, $this->user->home_page)];

        return [
            'redirect' => $redirectTo,
            'flash' => $flash,
        ];
    }
}
