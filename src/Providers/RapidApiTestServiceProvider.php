<?php

namespace Furkanakkulak\Rapidapitest\Providers;

use Furkanakkulak\Rapidapitest\Http\RapidApi\MovieService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;


class RapidApiTestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $envFilePath = base_path('.env');

        $envContents = File::get($envFilePath);

        if (strpos($envContents, 'RAPIDAPI_KEY=') === false) {
            File::append($envFilePath, "RAPIDAPI_KEY=your_api_key_here\n");
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/rapidapitest.php',
            'rapidapitest'
        );

        $this->app->bind(MovieService::class, function ($app) {
            $apiKey = config('rapidapitest.api_key');
            return new MovieService($apiKey);
        });

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->app['router']->aliasMiddleware('HandleApiErrors', \Furkanakkulak\Rapidapitest\Http\Middleware\HandleApiErrors::class);
    }
}
