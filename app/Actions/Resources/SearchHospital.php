<?php

namespace App\Actions\Resources;

use App\Models\Resources\Hospital;
use App\Traits\AvatarLinkable;

class SearchHospital
{
    use AvatarLinkable;

    public function __invoke(?string $search): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        if (! $search) {
            return [];
        }

        return Hospital::query()
            ->where('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get()
            ->transform(fn ($h) => $h->name)
            ->toArray();
    }
}
