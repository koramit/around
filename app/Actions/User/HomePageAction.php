<?php

namespace App\Actions\User;

use App\Extensions\Auth\AvatarUser;
use App\Traits\AvatarLinkable;
use App\Traits\HomePageSelectable;
use Illuminate\Support\Facades\Http;

class HomePageAction
{
    use HomePageSelectable, AvatarLinkable;

    public function __invoke(mixed $user, string $routeName): array
    {
        $link = $this->shouldLinkAvatar($user, $routeName);
        if ($link !== false) {

            return $link;
        }


        // ['icon' => 'graduation-cap', 'label' => 'Kidney club', 'route' => route('kidney-club'), 'can' => true],
        // ['icon' => 'graduation-cap', 'label' => 'Club Nephro', 'route' => 'procedures', 'can' => true],
        // ['icon' => 'box', 'label' => 'Code Drive', 'route' => 'procedures', 'can' => true],
        return [
            'page-title' => __('My Desk'),
            'main-menu-links' => collect([
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => $user->can('view_any_patients')],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => $user->can('view_any_patients')],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => $user->can('view_any_patients')],
            ])->filter(fn ($link) => $link['can'])->values(),
            'action-menu' => [
                $this->getSetHomePageActionMenu($routeName, $user),
            ]
        ];
    }
}
