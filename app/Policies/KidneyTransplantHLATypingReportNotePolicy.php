<?php

namespace App\Policies;

use App\Models\Notes\KidneyTransplantHLATypingReportNote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class KidneyTransplantHLATypingReportNotePolicy
{
    use HandlesAuthorization;

    public function view(User $user, KidneyTransplantHLATypingReportNote $kidneyTransplantHLATypingReportNote): Response|bool
    {
        //
    }

    public function update(User $user, KidneyTransplantHLATypingReportNote $report): Response|bool
    {
        return $report->author_id === $user->id // and not published
            ? Response::allow()
            : Response::deny('You are not allowed to update this report.');
    }
}
