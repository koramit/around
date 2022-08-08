<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord;
use Illuminate\Database\Eloquent\Collection;

class IdleCaseAction
{
    public function __invoke(string $search): Collection
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return collect([]); // call api
        }

        $ilike = config('database.ilike');

        return AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta', 'patient_id'])
            ->with(['patient:id,hn,profile'])
            ->whereDoesntHave('orders', fn ($q) => $q->activeStatuses())
            ->where(fn ($q) => $q->where('meta->name', $ilike, '%'.$search.'%')
                ->orWhere('meta->hn', $ilike, '%'.$search.'%')
            )->get()
            ->transform(fn ($c) => [
                'key' => implode('|', [$c->hashed_key, $c->patient->hn, $c->patient->profile['document_id']]),
                'value' => "HN {$c->meta['hn']} {$c->patient->full_name}",
            ]);
    }
}
