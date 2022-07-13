<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderScheduleAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['date_note' => 'required|date'])->validate();

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('reschedule', $note)) {
            abort(403);
        }

        $ensureSlotAvailable = (new SlotAvailableAction)([
            'date_note' => $validated['date_note'],
            'dialysis_type' => $note->meta['dialysis_type'],
            'dialysis_at' => $note->place_name,
        ]);

        if (! $ensureSlotAvailable['available']) {
            return [
                'type' => 'danger',
                'title' => 'Cannot reschedule.',
                'message' => 'Slot not available on '.now()->create($validated['date_note'])->format('M j'),
            ];
        }

        // check if reschedule from today maybe need request
        if ($note->date_note->format('Y-m-d') === $this->TODAY) {
            return [
                'type' => 'warning',
                'title' => 'Cannot reschedule.',
                'message' => 'Unhandled reschedule from today.',
            ];
        }

        $message = [
            'type' => 'info',
            'title' => 'Reschedule successful.',
            'message' => 'Form '.$note->date_note->format('M j').' to '.now()->create($validated['date_note'])->format('M j'),
        ];
        $note->update(['date_note' => $validated['date_note']]);

        return $message;
    }
}
