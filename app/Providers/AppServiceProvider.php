<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-settings', function (\App\Models\User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-locations', [\App\Policies\LocationPolicy::class, 'manage']);

        Gate::define('manage-catalog', fn (\App\Models\User $user) => $user->isAdmin());
    }
}
