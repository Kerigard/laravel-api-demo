<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        AnonymousResourceCollection::macro('paginationInformation', function ($request, $paginated, $default) {
            unset($default['links'], $default['meta']['links'], $default['meta']['path']);

            return $default;
        });
    }
}
