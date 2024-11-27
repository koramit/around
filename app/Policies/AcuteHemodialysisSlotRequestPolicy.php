<?php

namespace App\Policies;

use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcuteHemodialysisSlotRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct() {}

    public function approve(User $user, AcuteHemodialysisSlotRequest $request): bool
    {
        return $request->status == 'pending' && $user->hasAbility($request->authority_ability_id);
    }

    public function cancel(User $user, AcuteHemodialysisSlotRequest $request): bool
    {
        return $request->status == 'pending' && $request->requester_id === $user->id;
    }
}
