<?php

namespace Avant\Auth;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\LaravelPassport\Provider as LaravelPassportProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/services.php', 'services');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function boot(): void
    {
        Event::listen(fn (SocialiteWasCalled $event) => $event
            ->extendSocialite('avant-auth', LaravelPassportProvider::class)
        );
    }
}