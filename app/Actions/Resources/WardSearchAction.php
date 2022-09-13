<?php

namespace App\Actions\Resources;

use App\Models\Resources\Ward;
use App\Traits\AvatarLinkable;

class WardSearchAction
{
    use AvatarLinkable;

    public function __invoke(?string $search)
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if (! $search) {
            return [];
        }

        return Ward::query()
            ->select('name')
            ->search($search)
            ->get()
            ->pluck('name');
    }
}
