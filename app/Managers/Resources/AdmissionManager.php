<?php

namespace App\Managers\Resources;

use App\Models\Resources\Admission;
use App\Models\Resources\Ward;
use Illuminate\Support\Facades\Log;

class AdmissionManager
{
    public function manage(string $key, bool $recently = false)
    {
        $api = app('App\Contracts\PatientAPI');

        if ($recently) {
            $admissionData = $api->recentlyAdmission($key);
            if (! $admissionData['found']) {
                return $admissionData;
            }
            $an = $admissionData['an'];
        } else {
            $admissionData = $api->getAdmission($key);
            $an = $key;
        }

        $admission = Admission::findByHashedId($an)->withPlaceName()->first();

        if ($admission) {
            // update
            $admission->meta['discharge_type'] = $admissionData['discharge_type_name'] ?? null;
            $admission->meta['discharge_status'] = $admissionData['discharge_status_name'] ?? null;
            $admission->dismissed_at = $admissionData['dismissed_at'] ?? null;
            $admission->save();

            return [
                'found' => true,
                'admission' => $admission,
            ];
        }

        if (! $admissionData['found']) {
            return $admissionData;
        }

        // create
        $patient = (new PatientManager())->manage($admissionData['hn']);
        if (! $patient['found']) {
            Log::info($an.' hn from admission but not found in patient api');

            return $patient; // rare case
        }

        $ward = $this->maintainWard($admissionData);

        $admission = $patient['patient']->admissions()->create([
            'an' => $an,
            'meta' => [
                'attending' => $admissionData['attending_name'] ?? null,
                'discharge_status' => $admissionData['discharge_status_name'] ?? null,
                'discharge_type' => $admissionData['discharge_type_name'] ?? null,
            ],
            'encountered_at' => $admissionData['encountered_at'],
            'dismissed_at' => $admissionData['dismissed_at'],
            'ward_id' => $ward->id,
        ]);
        $admission->place_name = $ward->name;

        return [
            'found' => true,
            'admission' => $admission,
        ];
    }

    protected function maintainWard(array &$data): Ward
    {
        if ($ward = Ward::where('name_ref', $data['ward_name'])->first()) {
            return $ward;
        }

        return Ward::create([
            'name' => $data['ward_name'],
            'name_short' => $data['ward_name_short'],
            'name_ref' => $data['ward_name'],
        ]);
    }
}
