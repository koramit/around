<?php

namespace App\Actions\Resources;

use App\Contracts\PatientAPI;
use App\Rules\HnExistsInPatients;
use Illuminate\Support\Facades\Validator;

class CovidLabAction
{
    public function __invoke(array $data, PatientAPI $api): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['hn' => 'required|digits:8'])->validate();
        $key = "{$validated['hn']}-check-covid-lab";
        if ($data['refresh'] ?? false) {
            cache()->forget($key);
        }

        $cached = cache()->remember(
            key: $key,
            ttl: now()->addHour(),
            callback: function () use ($data, $api) {
                $validated = Validator::make($data, ['hn' => new HnExistsInPatients])->validate();
                $data = $api->checkCovidLab($validated['hn']) + ['when' => now()];
                if (! isset($data['labs'])) {
                    return $data;
                }
                $data['labs'] = collect($data['labs'])->transform(fn ($v) => [
                    'result' => $v['result'],
                    'name' => $v['name'] ?? '',
                    'date_lab' => now()->create($v['date_lab'])->format('j M Y'),
                ]);

                return $data;
            }
        );
        $cached['when'] = $cached['when']->diffForHumans();

        return $cached;
    }
}
