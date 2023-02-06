<?php

namespace App\Managers\Resources;

use App\Models\Resources\Patient;
use Illuminate\Support\Facades\Log;

class PatientManager
{
    public function manage(string $hn, bool $forceUpdate = false): array
    {
        if ($cachePatient = cache("api-get-patient-$hn")) {
            return [
                'found' => true,
                'patient' => $cachePatient,
            ];
        }

        $api = app('App\Contracts\PatientAPI');

        $patient = Patient::query()->findByHashKey($hn)->first();
        if (! $patient) {
            $data = $api->getPatient($hn);
            if (! $data['found']) {
                return $data;
            }

            $patient = new Patient();
            $patient->hn = $data['hn'];
            $patient->dob = $data['dob'];
            $patient->alive = $data['alive'];
            $patient->gender = $data['gender'] === 'male';
            unset($data['ok'], $data['found'], $data['alive'], $data['hn'], $data['dob'], $data['gender']);
            $patient->profile = $data;
            $patient->save();

            cache()->put("api-get-patient-$hn", $patient, 60);

            return [
                'found' => true,
                'patient' => $patient,
            ];
        }

        // determine if update needed
        if ($patient->updated_at->diffInDays(now()) <= 30 && ! $forceUpdate) {
            return [
                'found' => true,
                'patient' => $patient,
            ];
        }

        $data = $api->getPatient($hn);
        if (! $data['found']) {
            Log::info($hn.' hn canceled or something went wrong or call repeat call too early');

            return [
                'found' => true,
                'patient' => $patient,
            ];
        }

        unset($data['ok'], $data['found']);
        $this->update($patient, $data);

        return [
            'found' => true,
            'patient' => $patient,
        ];
    }

    public function update(Patient $patient, array $profile): void
    {
        if (
            $patient->profile['title'] !== $profile['title']
            || $patient->profile['first_name'] !== $profile['first_name']
            || $patient->profile['middle_name'] !== $profile['middle_name']
            || $patient->profile['last_name'] !== $profile['last_name']
        ) {
            if (! isset($patient->profile['old_name'])) {
                $patient->profile['old_name'] = [];
            }
            $patient->profile['old_name'][] = implode(' ', [$patient->profile['title'], $patient->profile['first_name'], $patient->profile['last_name']]);
        }

        foreach ($profile as $key => $value) {
            if (isset($patient->profile[$key])) {
                $patient->profile[$key] = $value;
            }
        }
        $patient->alive = $profile['alive'];
        $patient->dob = $profile['dob'];
        $patient->save();
    }
}
