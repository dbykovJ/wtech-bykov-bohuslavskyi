<?php

namespace App\Providers;

use App\Services\Cart\GuestCartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

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
        $this->app['events']->listen(Login::class, function (Login $event) {
            app(GuestCartService::class)->mergeIntoDb($event->user->id);
        });
    }
}
