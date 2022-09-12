<?php

namespace App\Actions\Resources;

use App\Models\Resources\Person;
use App\Traits\AvatarLinkable;

class PersonSearchAction
{
    use AvatarLinkable;

    public function __invoke(array $filters)
    {
        if ($link = $this->shouldLinkAvatar($user)) {
            return $link;
        }

        return Person::query()
            ->select('name')
            ->filter($filters)
            ->get()
            ->pluck('name');
    }
}
