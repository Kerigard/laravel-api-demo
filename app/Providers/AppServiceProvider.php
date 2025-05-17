<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureApplication();
        $this->configureDatabaseAndModels();

        $this->registerLocalProviders();
    }

    private function configureApplication(): void
    {
        URL::forceHttps(str(config()->string('app.url'))->startsWith('https://'));
        Authenticate::redirectUsing(fn () => null);
    }

    private function configureDatabaseAndModels(): void
    {
        DB::prohibitDestructiveCommands((bool) $this->app->environment('production'));
        Model::shouldBeStrict(! $this->app->environment('production'));
        Model::automaticallyEagerLoadRelationships();
    }

    private function registerLocalProviders(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
