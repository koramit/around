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

    public function __invoke(array $data, string $hashedKey, User $user): AcuteHemodialysisOrderNote
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->withPlaceName('App\Models\Resources\Ward')->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('swap', $note)) {
            abort(403);
        }

        $validated = Validator::make($data, ['swap_with' => 'required|digits:4'])->validate();

        if (! $noteSwap = AcuteHemodialysisOrderNote::query()->activeStatuses()->where('meta->swap_code', $validated['swap_with'])->first()) {
            throw ValidationException::withMessages(['status' => 'Swap code not match']);
        }

        if ($note->meta['in_unit'] !== $noteSwap->meta['in_unit']) {
            throw ValidationException::withMessages(['status' => 'Acute dialysis slot type not match']);
        }

        $dateSwap = $note->date_note;
        $note->update(['date_note' => $noteSwap->date_note, 'meta->swap_code' => $this->genSwapCode()]);
        $noteSwap->update(['date_note' => $dateSwap, 'meta->swap_code' => $this->genSwapCode()]);

        return $note;
    }
}
