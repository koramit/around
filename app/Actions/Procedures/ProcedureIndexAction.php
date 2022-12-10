<?php

namespace App\Actions\Procedures;

use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;
use App\Traits\RegistryGroupRouteQueryable;

class ProcedureIndexAction
{
    use FlashDataGeneratable, HomePageSelectable, AvatarLinkable, RegistryGroupRouteQueryable;

    public function __invoke(mixed $user, string $routeName)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if ($user->registry_names->count() === 0) {
            abort(403);
        }

        $procedures = $this->getRoutesByRegistryTypeAndUser(registryType: 'procedures', user: $user);
        if ($procedures->count() === 0) {
            abort(403);
        }

        $redirectTo = null;
        if ($procedures->count() === 1) {
            $redirectTo = $procedures[0]['route'];
        }

        $flash = $this->getFlash(__('Procedures'), $user);
        $flash['action-menu'] = [$this->getSetHomePageActionMenu($routeName, $user->home_page)];

        return [
            'redirect' => $redirectTo,
            'flash' => $flash,
        ];
    }
}
