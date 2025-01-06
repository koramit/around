<?php

namespace App\Providers;

use App\Events\DocumentChangeRequestCreated;
use App\Events\DocumentChangeRequestUpdating;
use App\Events\Procedures\AcuteHemodialysis\AcuteHemodialysisOrderNoteUpdating;
use App\Listeners\HandleChangeToInactiveStatus;
use App\Listeners\NotifyApprovalResult;
use App\Listeners\NotifyNewRequest;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        DocumentChangeRequestCreated::class => [NotifyNewRequest::class],
        DocumentChangeRequestUpdating::class => [NotifyApprovalResult::class],
        AcuteHemodialysisOrderNoteUpdating::class => [HandleChangeToInactiveStatus::class],
    ];

    public function boot(): void {}

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
