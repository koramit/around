<?php

namespace App\Providers;

use App\Contracts\AuthenticationAPI;
use App\Contracts\PatientAPI;
use Hashids\Hashids;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();

        $this->app->bind(AuthenticationAPI::class, config('app.authentication_provider'));

        $this->app->bind(PatientAPI::class, config('app.patient_provider'));

        $this->app->singleton(Hashids::class, fn () => new Hashids(salt: config('app.key')));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
