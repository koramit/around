<?php

namespace App\Actions\Procedures;

use App\Models\Resources\Registry;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;
use App\Traits\HomePageSelectable;

class ProcedureIndexAction
{
    use FlashDataGeneratable, HomePageSelectable, AvatarLinkable;

    public function __invoke(mixed $user, string $routeName)
    {
        $link = $this->shouldLinkAvatar();
        if ($link !== false) {
            return $link;
        }

        $procedureNameRoute = cache()->rememberForever('procedure-name-route', function () {
            return Registry::query()
                ->where('route', 'like', 'procedures.%')
                ->get()
                ->transform(fn (Registry $r) => [
                    'name' => $r->name,
                    'route' => $r->route,
                ]);
        });

        if ($user->registry_names->count() === 0) {
            abort(403);
        }

        $procedures = $procedureNameRoute->filter(fn ($r) => $user->registry_names->contains($r['name']))->values();

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
