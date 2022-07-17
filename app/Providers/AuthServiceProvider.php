<?php

namespace App\Providers;

use App\Extensions\Auth\AvatarUserProvider;
use App\Models\DocumentChangeRequests\AcuteHemodialysisSlotRequest;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Policies\AcuteHemodialysisOrderNotePolicy;
use App\Policies\AcuteHemodialysisSlotRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AcuteHemodialysisOrderNote::class => AcuteHemodialysisOrderNotePolicy::class,
        AcuteHemodialysisSlotRequest::class => AcuteHemodialysisSlotRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        if (config('auth.guards.web.provider') === 'avatars') {
            Auth::provider('avatars', fn () => new AvatarUserProvider(config('auth.avatars')));
        }

        Gate::before(function ($user, $ability) {
            if ($user->abilities->contains($ability)) {
                return true;
            }
            // check next policy or gate
        });
    }
}
