<?php

namespace App\Providers;

use App\Extensions\Auth\AvatarUserProvider;
use App\Models\Notes\AcuteHemodialysisOrderNote;
use App\Policies\AcuteHemodialysisOrderNotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AcuteHemodialysisOrderNote::class => AcuteHemodialysisOrderNotePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (config('auth.guards.web.provider') === 'avatars') {
            Auth::provider('avatars', fn () => new AvatarUserProvider(config('auth.avatars')));
        }
    }
}
