<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CaseRecordDestroyAction
{
    public function __invoke(array $data, string $hashedKey, User $user): array
    {
        if (config('auth.guards.web.provider') === 'avatar') {
            return []; // call api
        }

        $validated = Validator::make($data, ['reason' => 'required|string|max:255'])->validate();

        $caseRecord = AcuteHemodialysisCaseRecord::query()->findByUnhashKey($hashedKey)->firstOrFail();

        if ($user->cannot('destroy', $caseRecord)) {
            abort(403);
        }

        $caseRecord->update(['status' => 'canceled']);
        $caseRecord->actionLogs()->create([
            'action' => 'cancel',
            'actor_id' => $user->id,
            'payload' => ['reason' => $validated['reason']],
        ]);

        return [
            'type' => 'info',
            'title' => 'Order canceled successfully.',
            'message' => "Case HN {$caseRecord->meta['hn']} {$caseRecord->meta['name']} {$caseRecord->created_at->format('M j')} canceled",
        ];
    }
}
