<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;

class DialysisSessionUpdateAction
{
    use AvatarLinkable;

    public function __invoke(string $hashedKey, array $data, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
        }

        $order = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('perform', $order)) {
            abort(403);
        }

        cache()->put('no-view-log-uid-'.$user->id, true, 5);

        $validated = Validator::make($data, [
            'dialysis_at_chronic_unit' => 'required|bool',
            'extra_slot' => 'required|bool',
            'started_at' => 'nullable|date_format:H:i',
            'finished_at' => 'nullable|date_format:H:i',
        ])->validate();

        if (
            (! $order->meta['in_unit'] || ($order->meta['covid_case']))
            && $validated['dialysis_at_chronic_unit']
        ) {
            unset($validated['dialysis_at_chronic_unit']);
        }

        if ($order->status !== 'finished') {
            unset($validated['started_at'], $validated['finished_at']);
        }

        $diff = $this->diff($order->meta, $validated);

        if (count($diff) === 0) {
            return [
                'type' => 'info',
                'title' => 'No update',
                'message' => '',
            ];
        }

        $order->save(); // save by reference

        $order->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'change',
            'payload' => $diff,
        ]);

        return [
            'type' => 'success',
            'title' => 'Update successful.',
            'message' => '',
        ];
    }

    protected function diff($old, $new): array
    {
        $payload = [];
        foreach ($new as $key => $value) {
            $oldValue = $old[$key] ?? null;
            if ($oldValue !== $value) {
                $payload[] = [$key => [$oldValue, $value]];
                $old[$key] = $value;
            }
        }

        return $payload;
    }
}
