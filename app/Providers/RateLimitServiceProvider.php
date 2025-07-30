<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureApiV1();
    }

    protected function configureApiV1(): void
    {
        RateLimiter::for('api-v1', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api-v1-auth', function (Request $request) {
            return Limit::perMinute(6)->by($request->input('email') ?? $request->ip());
        });
    }
}
