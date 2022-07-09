<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrderDestroyAction extends AcuteHemodialysisAction
{
    /**
     * @todo IF status == scheduling then also expire coressponding request
     */
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        $note = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('destroy', $note)) {
            abort(403);
        }

        // IF status == scheduling then also expire coressponding request

        $note->update(['status' => 'canceled']);

        $note->actionLogs()->create([
            'actor_id' => $user->id,
            'action' => 'cancel',
            'payload' => ['reason' => $validated['reason']],
        ]);

        return [
            'type' => 'info',
            'title' => 'Order canceled successfully.',
            'message' => 'Order '.$note->meta['dialysis_type'].' on '.$note->date_note->format('M j').' canceled',
        ];
    }
}
