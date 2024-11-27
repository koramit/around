<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Casts\AcuteHemodialysisCaseRecordStatus;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Collection;

class IdleCaseAction
{
    use AvatarLinkable;

    public function __invoke(string $search): Collection|array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        return AcuteHemodialysisCaseRecord::query()
            ->select(['id', 'meta', 'patient_id'])
            ->where('status', (new AcuteHemodialysisCaseRecordStatus)->getCode('active'))
            ->with(['patient:id,hn,profile'])
            ->whereDoesntHave('orders', fn ($q) => $q->activeStatuses())
            ->metaSearchTerms($search)
            ->get()
            ->transform(fn ($c) => [
                'key' => implode('|', [$c->hashed_key, $c->patient->hn, $c->patient->profile['document_id']]),
                'value' => "HN {$c->meta['hn']} {$c->patient->full_name}",
            ]);
    }
}
