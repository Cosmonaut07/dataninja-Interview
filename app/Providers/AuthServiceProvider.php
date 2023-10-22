<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guards\CustomTokenGuard;
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
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
            Auth::extend('access_token', function ($app, $name, array $config) {
                $userProvider = app(TokenUserProvider::class);
                $request = app('request');
                return new CustomTokenGuard($userProvider, $request, $config);
            });
    }
}
