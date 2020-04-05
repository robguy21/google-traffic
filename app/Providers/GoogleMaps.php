<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GoogleMaps as GoogleMapsService;

class GoogleMaps extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(GoogleMapsService::class, function() {
            return new GoogleMapsService(
                config('project.map.api_key'),
                config('project.map.destination')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
