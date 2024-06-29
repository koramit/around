<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\CovidInfoAPI;
use App\Contracts\PatientAPI;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PortalAPI implements AuthenticationAPI, CovidInfoAPI, PatientAPI
{
    public function getUser(string|int $id): array
    {
        if (is_numeric($id)) {
            $data = ['org_id' => $id];
        } else {
            $data = ['login' => $id];
        }

        return $this->makePost('user', $data);
    }

    public function authenticate(string $login, string $password): array
    {
        return $this->makePost('authenticate', [
            'login' => $login,
            'password' => $password,
        ]);
    }

    public function getPatient(string|int $hn): array
    {
        $data = $this->makePost('patient-with-sensitive-data', ['hn' => $hn]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = __('service.item_not_found', ['item' => 'HN']);
            unset($data['body']);

            return $data;
        }

        return $data;
    }

    public function getAdmission(string|int $an): array
    {
        $data = $this->makePost('admission-with-sensitive-data', ['an' => $an]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = __('service.item_not_found', ['item' => 'AN']);
            unset($data['body']);

            return $data;
        }

        return $this->handleAdmission($data);
    }

    public function getPatientAdmissions(string|int $hn): array
    {
        return $this->makePost('patient-admissions-with-sensitive-data', ['hn' => $hn]);
    }

    public function getPatientRecentlyAdmission(string|int $hn): array
    {
        $data = $this->makePost('patient-recently-admission-with-sensitive-data', ['hn' => $hn]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (isset($data['found']) && $data['found']) { // error: not found
            return $this->handleAdmission($data);
        }

        $data['message'] = __('service.item_not_found', ['item' => 'admission']);
        if (isset($data['patient']) && $data['patient']['found']) { // error not found patient
            $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
            $data['patient']['location'] = $data['patient']['postcode'];

            return $data;
        }

        $data['patient']['message'] = __('service.item_not_found', ['item' => 'HN']);

        return $data;
    }

    public function getWard(int|string|null $number = null): array
    {
        return $this->makePost('ward-admissions', ['number' => $number]);
    }

    public function getAdmissionDischargeDate(array $data): array
    {
        return $this->makePost('admission-discharge-date', $data);
    }

    public function getAdmissionTransfers(int|string $an): array
    {
        return $this->makePost('admission-transfers', ['an' => $an]);
    }

    public function getItemize(string $category, string $search = ''): array
    {
        return $this->makePost('itemize', ['category' => $category, 'search' => $search]);
    }

    protected function makePost(string $route, array $data): array
    {
        try {
            $response = Http::withToken(config('services.portal_token'))
                ->retry(2, 200)
                ->withOptions(['verify' => config('services.portal_verify_ssl')])
                ->acceptJson()
                ->post(config('services.portal_base_url').$route, $data);
        } catch (RequestException $e) {
            $statusCode = $e->getCode();
            if ($statusCode === 422) {
                throw ValidationException::withMessages($e->response->json());
            } else {
                Log::error($route.'@portal '.$e->getMessage());

                return [
                    'ok' => false,
                    'status' => $statusCode,
                    'error' => $e->response->json(),
                    'body' => __('service.failed'),
                ];
            }
        }

        if ($response->successful() && ($response->json()['ok'] ?? false)) {
            return $response->json();
        }

        Log::error($route.'@portal '.$response->body());

        return [
            'ok' => false,
            'status' => $response->status(),
            'error' => $response->serverError() ? 'server' : 'client',
            'body' => __('service.failed'),
        ];
    }

    public function getUserById(int $id): array
    {
        return $this->getUser($id);
    }

    public function checkUserById(int $orgId): array
    {
        // TODO: Implement checkUserById() method.
        return [];
    }

    public function recentlyAdmission(string $hn): array
    {
        return $this->getPatientRecentlyAdmission($hn);
    }

    public function stayRecently(string $hn): array
    {
        // TODO: Implement stayRecently() method.
        return [];
    }

    protected function handleAdmission(array $data): array
    {
        $data['patient']['found'] = true;
        $data['attending_name'] = $data['attending'];
        $data['discharge_type_name'] = $data['discharge_type'];
        $data['discharge_status_name'] = $data['discharge_status'];
        $data['encountered_at'] = $data['admitted_at'] ? Carbon::parse($data['admitted_at'], 'asia/bangkok')->tz('UTC') : null;
        $data['dismissed_at'] = $data['discharged_at'] ? Carbon::parse($data['discharged_at'], 'asia/bangkok')->tz('UTC') : null;
        $data['patient']['marital_status_name'] = $data['patient']['marital_status'];
        $data['patient']['location'] = $data['patient']['postcode'];

        return $data;
    }

    public function checkCovidLab(int $hn): array
    {
        return $this->makePost('covid-19-pcr-labs', [
            'hn' => $hn,
            'date_lab' => '2023-09-22', // Carbon::now()->tz(7)->format('Y-m-d'),
        ]);
    }

    public function checkCovidVaccine(string $cid, $raw = false): array
    {
        return $this->makePost('covid-19-vaccinations', ['cid' => $cid]);
    }

    public function patientAdmitWards(int $an): array
    {
        return $this->makePost('admission-transfers', ['an' => $an]);
    }
}
