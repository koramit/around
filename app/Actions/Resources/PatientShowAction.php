<?php

namespace App\Actions\Resources;

use App\Managers\Resources\PatientManager;
use App\Traits\AvatarLinkable;

class PatientShowAction
{
    use AvatarLinkable;

    public function __invoke(string $hn): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $patient = (new PatientManager)->manage($hn);

        if (! $patient['found']) {
            return $patient;
        }

        $patient = $patient['patient'];

        return [
            'found' => true,
            'name' => $patient->full_name,
        ];
    }
}
