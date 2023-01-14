<?php

namespace App\Actions\Resources;

use App\Managers\Resources\AdmissionManager;
use App\Traits\AvatarLinkable;

class AdmissionShowAction
{
    use AvatarLinkable;

    public function __invoke(string $key): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $admission = (new AdmissionManager())->manage($key);

        if (! $admission['found']) {
            return $admission;
        }

        return [
            'found' => true,
            'hn' => $admission['admission']->patient->hn,
            'an' => $admission['admission']->an,
            'name' => $admission['admission']->patient->full_name,
            'gender' => $admission['admission']->patient->gender,
            'age' => $admission['admission']->patient_age_at_encounter_text,
            'ward_admit' => $admission['admission']->place_name,
            'admitted_at' => $admission['admission']->encountered_at_for_humans,
            'discharged_at' => $admission['admission']->dismissed_at_for_humans,
        ];
    }
}
