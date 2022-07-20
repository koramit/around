<?php

namespace App\Actions\Resources;

use App\Contracts\PatientAPI;
use Illuminate\Support\Facades\Validator;

class CovidVaccineAction
{
    public function __invoke(array $data, PatientAPI $api): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['cid' => 'required|string|max:13'])->validate();
        $key = "{$validated['cid']}-check-covid-vaccine";
        if (($data['refresh'] ?? false) || !(cache($key)['ok'] ?? false)) {
            cache()->forget($key);
        }

        $cached = cache()->remember(
            key: $key,
            ttl: now()->addDay(),
            callback: function () use($validated, $api) {
                $data = $api->checkCovidVaccine($validated['cid']) + ['when' => now()];
                if (!isset($data['vaccinations'])) {
                    return $data;
                }
                $data['vaccinations'] = collect($data['vaccinations'])->transform(fn ($v) => [
                    'brand' => $v['brand'],
                    'place' => $v['place'],
                    'date' => now()->create($v['date'])->format('j M Y'),
                ]);

                return $data;
            }
        );
        $cached['when'] = $cached['when']->diffForHumans();

        return $cached;
    }
}
