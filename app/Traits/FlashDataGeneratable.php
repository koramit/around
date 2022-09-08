<?php

namespace App\Traits;

use App\Models\User;

trait FlashDataGeneratable
{
    protected function getFlash(string $title, User $user): array
    {
        return [
            'page-title' => $title,
            'main-menu-links' => collect([
                ['icon' => 'patient', 'label' => 'Patients', 'route' => route('patients'), 'can' => $user->can('view_any_patients')],
                ['icon' => 'clinic', 'label' => 'Clinics', 'route' => route('clinics'), 'can' => $user->can('view_any_patients')],
                ['icon' => 'procedure', 'label' => 'Procedures', 'route' => route('procedures.index'), 'can' => $user->can('view_any_patients')],
            ])->filter(fn ($link) => $link['can'])->values(),
            'action-menu' => [],
        ];
    }
}
