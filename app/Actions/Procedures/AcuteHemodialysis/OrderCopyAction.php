<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;

class OrderCopyAction
{
    public function __invoke(string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        /** @var AcuteHemodialysisCaseRecord $caseRecord */
        $caseRecord = $order->caseRecord;
        if ($caseRecord->orders()->count() - 1 === 0) {
            return [
                'found' => false,
            ];
        }

        $form = $order->form;
        $copies = $caseRecord->orders()
            ->where('id', '<>', $order->id)
            ->where('meta->submitted', true)
            ->latest()
            ->get();

        $records = [];
        foreach (['hd', 'hf', 'tpe', 'sledd'] as $type) {
            if (! isset($form[$type])) {
                continue;
            }

            $index = $copies->search(fn ($c) => isset($c->form[$type]));
            if ($index === false) {
                continue;
            }

            $copy = $copies[$index]->form;

            foreach (array_keys($form[$type]) as $key) {
                $form[$type][$key] = $copy[$type][$key] ?? null;
            }

            $records[] = [
                'type' => $type,
                'form' => $form[$type],
                'date' => $copies[$index]->date_note->format('M j'),
            ];
        }

        return [
            'found' => count($records) > 0,
            'records' => $records,
        ];
    }
}
