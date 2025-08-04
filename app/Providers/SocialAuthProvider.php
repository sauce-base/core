<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class SocialAuthProvider extends ServiceProvider
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
        Event::listen(function (\SocialiteProviders\Manager\SocialiteWasCalled $event) {
            $providers = config('app.social_providers', []);

            foreach ($providers as $key => $provider) {
                if ($provider['enabled']) {
                    $event->extendSocialite($key, $provider['provider']);
                }
            }
        });
    }
}
