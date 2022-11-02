<?php

namespace App\Traits;

use App\Models\User;

trait FlashDataGeneratable
{
    use RegistryGroupRouteQueryable;

    protected function getFlash(string $title, User $user): array
    {
        return [
            'page-title' => $title,
            'main-menu-links' => collect([
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => $user->can('view_any_patients')],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics.index'), 'can' => $this->getRoutesByRegistryTypeAndUser('clinics', $user)->count()],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => $this->getRoutesByRegistryTypeAndUser('procedures', $user)->count()],
                ['icon' => 'lab', 'label' => 'Labs', 'route' => route('labs.index'), 'can' => $this->getRoutesByRegistryTypeAndUser('labs', $user)->count()],
            ])->filter(fn ($link) => $link['can'])->values(),
            'action-menu' => [],
        ];
    }
}
