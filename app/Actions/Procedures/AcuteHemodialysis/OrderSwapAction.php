<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use App\Traits\AcuteHemodialysis\SwapCodeGeneratable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderSwapAction extends AcuteHemodialysisAction
{
    use SwapCodeGeneratable;

    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('swap', $note)) {
            abort(403);
        }

        $validated = Validator::make($data, ['swap_with' => 'required|digits:4'])->validate();

        $reply = [];

        if (! $noteSwap = AcuteHemodialysisOrderNote::query()->activeStatuses()->where('meta->swap_code', $validated['swap_with'])->first()) {
            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot',
                'message' => 'Swap code not match',
            ];
            // throw ValidationException::withMessages(['status' => 'Swap code not match']);
        }

        if ($note->meta['in_unit'] !== $noteSwap->meta['in_unit']) {
            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot',
                'message' => 'Cannot swap HD unit slot with ward slot',
            ];
            // throw ValidationException::withMessages(['status' => 'Acute dialysis slot type not match']);
        }

        if ($note->date_note->format('Y-m-d') === $this->TODAY || $noteSwap->date_note->format('Y-m-d') === $this->TODAY) {
            return [
                'type' => 'danger',
                'title' => 'Cannot swap slot',
                'message' => 'Swap with today slot not available yet',
            ];
        }

        $reply = [
            'type' => 'info',
            'title' => 'Swap successful',
            'message' => 'Swap '.$note->meta['name'].' on '.$note->date_note->format('M j').' WITH '.$noteSwap->meta['name'].' on '.$noteSwap->date_note->format('M j'),
        ];

        $dateSwap = $note->date_note;
        $note->update(['date_note' => $noteSwap->date_note, 'meta->swap_code' => $this->genSwapCode()]);
        $noteSwap->update(['date_note' => $dateSwap, 'meta->swap_code' => $this->genSwapCode()]);

        return $reply;
    }
}
