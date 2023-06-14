<?php

namespace App\Providers;

use App\Contracts\AuthenticationAPI;
use App\Contracts\CovidInfoAPI;
use App\Contracts\PatientAPI;
use Hashids\Hashids;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();

        $this->app->bind(AuthenticationAPI::class, config('app.authentication_provider'));

        $this->app->bind(PatientAPI::class, config('app.patient_provider'));

        $this->app->bind(CovidInfoAPI::class, config('app.covid_info_provider'));

        $this->app->singleton(Hashids::class, fn () => new Hashids(salt: config('app.key')));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Model::preventLazyLoading(! $this->app->isProduction());

        Model::preventAccessingMissingAttributes(! $this->app->isProduction());

        /*Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
            $class = get_class($model);
            info("Attempted to lazy load [{$relation}] on model [{$class}].");
        });*/

        $threshold = config('app.query_time_threshold');
        DB::whenQueryingForLongerThan($threshold, function (Connection $connection, QueryExecuted $event) use ($threshold) {
            Log::warning("Database queries exceeded {$threshold} milliseconds on {$connection->getName()} : $event->sql");
        });
    }
}
