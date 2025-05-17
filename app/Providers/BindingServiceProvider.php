<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BindingServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public $bindings = [
        \App\Contracts\Auth\CreatesAuthToken::class => \App\Actions\Auth\CreateJWTToken::class,
        \App\Contracts\Auth\CreatesRefreshToken::class => \App\Actions\Auth\CreateRefreshToken::class,
        \App\Contracts\Auth\RevokesRefreshToken::class => \App\Actions\Auth\RevokeRefreshToken::class,
    ];

    public function boot(): void
    {
        //
    }
}
