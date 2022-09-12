<?php

namespace App\Actions\Resources;

use App\Rules\HnExistsInPatients;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;

class CovidLabAction
{
    use AvatarLinkable;

    public function __invoke(array $data): array
    {
        if ($link = $this->shouldLinkAvatar($user)) {
            return $link;
        }

        $validated = Validator::make($data, ['hn' => 'required|digits:8'])->validate();
        $key = "{$validated['hn']}-check-covid-lab";
        if ($data['refresh'] ?? false) {
            cache()->forget($key);
        }

        $api = app('App\Contracts\PatientAPI');
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
