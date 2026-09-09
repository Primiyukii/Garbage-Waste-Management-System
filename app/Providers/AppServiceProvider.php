<?php

namespace App\Providers;
use App\Models\Bill;
use App\Models\Collector;
use App\Models\CollectionSchedule;
use App\Models\Customer;
use App\Models\Payment;
use App\Observers\BillObserver;
use App\Observers\CollectorObserver;
use App\Observers\CollectionScheduleObserver;
use App\Observers\CustomerObserver;
use App\Observers\PaymentObserver;
use App\Models\User;
use App\Observers\UserObserver;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;

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

        Gate::define('manage-catalog', fn(\App\Models\User $user) => $user->isAdmin());

        Customer::observe(CustomerObserver::class);
        Collector::observe(CollectorObserver::class);
        CollectionSchedule::observe(CollectionScheduleObserver::class);
        Bill::observe(BillObserver::class);
        Payment::observe(PaymentObserver::class);
        User::observe(UserObserver::class);

        Event::listen(Login::class, function (Login $event) {
            app(\App\Services\AuditService::class)->log('auth.login', "User {$event->user->name} logged in");
        });

        Event::listen(Logout::class, function (Logout $event) {
            app(\App\Services\AuditService::class)->log('auth.logout', "User {$event->user->name} logged out");
        });
    }
}
