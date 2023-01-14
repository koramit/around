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
        return true;
    }

    public function edit(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $report->author_id === $user->id && $report->status === 'draft';
    }

    public function update(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $this->edit($user, $report);
    }

    public function destroy(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $this->edit($user, $report);
    }

    public function publish(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $this->edit($user, $report);
    }

    public function addendum(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $user->can('addendum_kt_hla_typing_report')
            && collect(['published', 'edited'])->contains($report->status);
    }

    public function cancel(User $user, KidneyTransplantHLATypingReportNote $report): bool
    {
        return $this->addendum($user, $report);
    }
}
