<?php

namespace App\Actions\Procedures\AcuteHemodialysis;

use App\Models\Registries\AcuteHemodialysisCaseRecord;
use App\Traits\AvatarLinkable;
use Illuminate\Support\Facades\Validator;

class CaseRecordDestroyAction
{
    use AvatarLinkable;

    public function __invoke(array $data, string $hashedKey, mixed $user): array
    {
        if (($link = $this->shouldLinkAvatar()) !== false) {
            return $link;
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
