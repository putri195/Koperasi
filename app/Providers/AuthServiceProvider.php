<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\User' => 'App\Policies\UserPolicy', // Contoh: Uncoment jika ada policy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Otorisasi gate dapat didefinisikan di sini
        // Gate::define('view-dashboard', function ($user) {
        //     return $user->isAdmin();
        // });
    }
}