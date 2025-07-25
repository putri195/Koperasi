<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Keycloak\Provider as KeycloakProvider;

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
        // Extend Socialite for Keycloak
        Socialite::extend('keycloak', function ($app) {
            $config = $app['config']['services.keycloak'];

            return Socialite::buildProvider(KeycloakProvider::class, [
                'client_id'     => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'redirect'      => $config['redirect'],
                'base_url'      => $config['base_url'],   // PENTING
                'realms'        => $config['realms'],     // PENTING
            ]);
        });

        // Share user globally to all views
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }
}