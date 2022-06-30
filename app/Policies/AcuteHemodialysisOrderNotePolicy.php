<?php

namespace App\Policies;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcuteHemodialysisOrderNotePolicy
{
    use HandlesAuthorization;

    protected $updateNotAllowStatuses;

    public function __construct()
    {
        $this->updateNotAllowStatuses = collect((new AcuteHemodialysisOrderStatus)->getUpdateNotAllowStatuses());
    }

    public function edit(User $user, Note $note)
    {
        return $user->id === $note->user_id
            && ! $this->updateNotAllowStatuses->contains($note->status);
    }

    public function update(User $user, Note $note)
    {
        return $user->id === $note->user_id
            && ! $this->updateNotAllowStatuses->contains($note->status);
    }

    public function submit(User $user, Note $note)
    {
        return $user->id === $note->user_id
            && ! $this->updateNotAllowStatuses->contains($note->status);
    }

    public function reschedule(User $user, Note $note)
    {
        return $user->id === $note->user_id
            && ! $this->updateNotAllowStatuses->contains($note->status);
    }

    public function destroy(User $user, Note $note)
    {
        return $user->id === $note->user_id
            && ! $this->updateNotAllowStatuses->contains($note->status);
    }
}
