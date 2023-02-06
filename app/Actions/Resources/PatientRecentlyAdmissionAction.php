<?php

namespace App\Actions\Resources;

use App\Managers\Resources\AdmissionManager;
use App\Managers\Resources\PatientStayManager;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Carbon;

class PatientRecentlyAdmissionAction
{
    use AvatarLinkable;

    public function __invoke(string $hn): array
    {
        if ($link = $this->shouldLinkAvatar()) {
            return $link;
        }

        $admission = (new AdmissionManager)->manage(key: $hn, recently: true);
        // @TODO: delete code Stay related
        /*$stay = (new PatientStayManager)->manage($hn);
        $stayLocation = 'แพทย์เวร ';
        if ($stay['found']) {
            $stayLocation .= (($stay['zone_name'] ?? '').' '.($stay['tag_number'] ?? ''));
        }*/

        if (! $admission['found']) {
            if (! ($admission['patient']['found'] ?? false)) {
                return [
                    'found' => false,
                    'hn' => null,
                ];
            }

            return [
                'found' => false,
                'hn' => $admission['patient']['hn'],
                'name' => $admission['patient']['first_name'].' '.$admission['patient']['last_name'],
                'gender' => $admission['patient']['gender'],
                'age' => $admission['patient']['dob']
                    ? Carbon::create($admission['patient']['dob'])->diffInYears()
                    : null,
                'location' => 'แพทย์เวร/ER ?',
                // 'location' => ($stay['found'] ?? false) ? $stayLocation : 'ER ?',
                'admitted_at' => null,
            ];
        }

        /*if ($admission['admission']->dismissed_at && ($stay['found'] ?? false)) {
            return [
                'found' => false,
                'hn' => $admission['admission']->patient->hn,
                'name' => $admission['admission']->patient->full_name,
                'gender' => $admission['admission']->patient->gender,
                'age' => $admission['admission']->patient->patient_age_at_encounter_text,
                'location' => $stayLocation,
                'admitted_at' => null,
            ];
        }*/

        return [
            'found' => true,
            'an' => $admission['admission']->an,
            'hn' => $admission['admission']->patient->hn,
            'name' => $admission['admission']->patient->full_name,
            'gender' => $admission['admission']->patient->gender,
            'age' => $admission['admission']->patient_age_at_encounter_text,
            'ward_admit' => $admission['admission']->place_name,
            'admitted_at' => $admission['admission']->encountered_at_for_humans,
            'discharged_at' => $admission['admission']->dismissed_at_for_humans,
        ];
    }
}
