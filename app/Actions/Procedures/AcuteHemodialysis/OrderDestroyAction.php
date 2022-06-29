<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;

class OrderDestroyAction extends AcuteHemodialysisAction
{
    public function __invoke(array $data, string $hashedKey, User $user)
    {
        if (config('auth.gurads.web.provider') === 'avatar') {
            return []; // call api
        }

        $note = AcuteHemodialysisOrderNote::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('update', $note)) {
            abort(403);
        }

        $note->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        return $note;
    }
}
