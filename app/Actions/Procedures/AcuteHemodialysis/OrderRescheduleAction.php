<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderRescheduleAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
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

        if ($ensureSlotAvailable['available']) {
            $message = [
                'type' => $ensureSlotAvailable['available'] ? 'info' : 'danger',
                'title' => 'Reschedule successful.',
                'message' => 'Form '.$note->date_note->format('M j').' to '.now()->create($validated['date_note'])->format('M j'),
            ];
            $note->update(['date_note' => $validated['date_note']]);
        } else {
            $message = [
                'type' => $ensureSlotAvailable['available'] ? 'info' : 'danger',
                'title' => 'Cannot reschedule.',
                'message' => 'Slot not available on '.now()->create($validated['date_note'])->format('M j'),
            ];
        }

        return $message;
    }
}
