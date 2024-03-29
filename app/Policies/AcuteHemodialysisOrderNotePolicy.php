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
            && $this->status->getEditNotAllowStatuses()->doesntContain($note->status);
    }

    public function update(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && $this->status->getUpdateNotAllowStatuses()->doesntContain($note->status)
            && ! ($note->meta['submitted'] ?? false);
    }

    public function submit(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && $this->status->getSubmitNotAllowStatuses()->doesntContain($note->status);
    }

    public function reschedule(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && $this->status->getScheduleNotAllowStatuses()->doesntContain($note->status);
    }

    public function swap(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && $this->status->getScheduleNotAllowStatuses()->doesntContain($note->status);
    }

    public function view(User $user): bool
    {
        return $user->can('view_acute_hemodialysis_order');
    }

    public function destroy(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->id === $note->author_id
            && $this->status->getEditNotAllowStatuses()->doesntContain($note->status);
    }

    public function start(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        if (
            ! $user->can('perform_acute_hemodialysis_order')
            || $note->status !== 'submitted'
        ) {
            return false;
        }

        $since = $note->date_note->clone()->subHours(7);
        // in charge can start session after order date
        if (
            $user->can('start_session_days_after_date_note')
            && now()->greaterThan($since)
        ) {
            return true;
        }

        $until = $note->date_note->clone()->addDay();

        return now()->between($since, $until);
    }

    public function finish(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->can('perform_acute_hemodialysis_order')
            && $note->status === 'started';
    }

    public function perform(User $user, AcuteHemodialysisOrderNote $note): bool
    {
        return $user->can('perform_acute_hemodialysis_order')
            && $this->status->getPerformNotAllowStatuses()->doesntContain($note->status);
    }
}
