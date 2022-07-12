<?php

namespace App\APIs;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubHannahAPI implements PatientAPI, AuthenticationAPI
{
    public function authenticate(string $login, string $password): array
    {
        $data = $this->makePost(route: 'auth', form: ['login' => $login, 'password' => $password]);
        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => ($data['status'] ?? 500) === 400 ? __('auth.failed') : __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = $data['message'] ?? __('auth.failed');
            unset($data['UserInfo']);
            unset($data['body']);

            return $data;
        }

        return [
            'ok' => $data['ok'],
            'found' => $data['found'],
            'username' => $data['login'],
            'name' => $data['full_name'],
            'name_en' => $data['full_name_en'],
            'email' => $data['email'],
            'org_id' => $data['org_id'],
            'tel_no' => $data['tel_no'] ?? null,
            'document_id' => null,
            'org_division_name' => $data['division_name'],
            'org_position_title' => $data['position_name'],
            'remark' => $data['remark'],
            'password_expires_in_days' => $data['password_expires_in_days'],
        ];
    }

    public function getUserById(string $id): array
    {
        $data = $this->makePost(route: 'user-by-id', form: ['org_id' => $id]);

        if (! $data || ! $data['ok']) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        if (! isset($data['found']) || ! $data['found']) {
            $data['message'] = $data['message'] ?? __('auth.failed');
            unset($data['UserInfo']);
            unset($data['body']);

            return $data;
        }

        return $data;
    }

    public function getPatient(string $hn): array
    {
        $data = $this->makePost(route: 'patient', form: ['hn' => $hn]);
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

    public function getAdmission(string $an): array
    {
        $data = $this->makePost(route: 'admission', form: ['an' => $an]);
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

    public function recentlyAdmission(string $hn): array
    {
        $data = $this->makePost(route: 'patient-recently-admit', form: ['hn' => $hn]);
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

    public function stayRecently(string $hn): array
    {
        $data = $this->makePost('api/stay-recently/'.$hn, []);

        if (! $data) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return $data;
    }

    public function checkUserById($orgId): array
    {
        $data = $this->makePost('user-status-by-id', ['org_id' => $orgId]);

        if (! $data) { // error: $data = null
            return [
                'found' => false,
                'message' => __('service.failed'),
            ];
        }

        return $data;
    }

    public function checkCovidLab($hn): array
    {
        return $this->makePost('npcr-covid', ['hn' => $hn]);
    }

    protected function makePost(string $route, array $form, int $timeout = 4): array
    {
        $headers = ['app' => config('services.SUBHANNAH_API_NAME'), 'token' => config('services.SUBHANNAH_API_TOKEN')];
        $options = ['timeout' => $timeout, 'verify' => false];
        try {
            $response = Http::withOptions($options)
                            ->withHeaders($headers)
                            ->post(config('services.SUBHANNAH_API_URL').$route, $form);
        } catch (Exception $e) {
            Log::error($route.'@hannah '.$e->getMessage());

            return ['ok' => false, 'status' => 408, 'error' => 'client', 'body' => 'Service is not available at the moment, please try again.'];
        }

        if ($response->successful()) {
            return $response->json();
        }

        Log::error($route.'@hannah '.$response->body().' '.$response->status());

        return [
            'ok' => false,
            'status' => $response->status(),
            'error' => $response->serverError() ? 'server' : 'client',
            'body' => 'Service is not available at the moment, please try again.',
        ];
    }

    /**
     * @param  array  $data
     * @return array
     */
    public function handleAdmission(array $data): array
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
}
