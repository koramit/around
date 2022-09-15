<?php

namespace App\Actions\User;

use App\Models\User;
use App\Traits\AvatarLinkable;
use App\Traits\FlashDataGeneratable;

class UserIndexAction
{
    use AvatarLinkable, FlashDataGeneratable;

    public function __invoke(array $filters, mixed $user)
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $flash = $this->getFlash(__('Manage User'), $user);
        $users = User::query()
            ->select(['id', 'full_name'])
            ->whereNotIn('id', [1, $user->id])
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('full_name', 'like', "%$search%"))
            ->orderBy('full_name')
            ->paginate($user->items_per_page)
            ->withQueryString()
            ->through(fn ($u) => [
                'id' => $u->hashed_key,
                'name' => $u->full_name,
                'get_route' => route('users.roles.show', $u->hashed_key),
            ]);

        return [
            'flash' => $flash,
            'users' => $users,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => null,
            ],
        ];
    }
}
