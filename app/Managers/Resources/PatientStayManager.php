<?php

namespace App\Managers\Resources;

class PatientStayManager
{
    public function manage(string $hn): array
    {
        $api = app('App\Contracts\PatientAPI');
        if ($stay = cache("api-get-recently-stay-$hn")) {
            return $stay;
        }

        $stay = $api->stayRecently($hn);

        if (!isset($stay['found'])) {
            return [
                'found' => false,
                'message' => 'error'
            ];
        }

        if ($stay['found']) {
            cache()->put("api-get-recently-stay-$hn", $stay, 60);
        }

        return $stay;
    }
}
