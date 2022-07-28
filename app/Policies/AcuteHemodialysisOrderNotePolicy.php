<?php

namespace App\Policies;

use App\Casts\AcuteHemodialysisOrderStatus;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcuteHemodialysisOrderNotePolicy
{
    use HandlesAuthorization;

    protected AcuteHemodialysisOrderStatus $status;

    public function __construct()
    {
        $this->status = new AcuteHemodialysisOrderStatus;
    }

    public function edit(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getEditNotAllowStatuses()->contains($note->status);
    }

    public function update(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getUpdateNotAllowStatuses()->contains($note->status)
            && ! ($note->meta['submitted'] ?? false);
    }

    public function submit(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getSubmitNotAllowStatuses()->contains($note->status);
    }

    public function reschedule(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getScheduleNotAllowStatuses()->contains($note->status);
    }

    public function swap(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getScheduleNotAllowStatuses()->contains($note->status);
    }

    public function view(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->can('view_acute_hemodialysis_order')
            && ! $this->status->getViewNotAllowStatuses()->contains($note->status);
    }

    public function destroy(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && ! $this->status->getEditNotAllowStatuses()->contains($note->status);
    }

    public function perform(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->can('perform_acute_hemodialysis_order')
            && ! $this->status->getViewNotAllowStatuses()->contains($note->status);
    }
}
