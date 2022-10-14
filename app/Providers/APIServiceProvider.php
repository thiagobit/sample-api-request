<?php

namespace App\Providers;

use App\Services\ApiRequest;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $httpClient = \Illuminate\Support\Facades\Http::baseUrl(config('api.url'));

        $this->app->singleton('product-api', function () use ($httpClient) {
            return new ApiRequest(config('api.resources.product'), $httpClient);
        });

        $this->app->singleton('order-api', function () use ($httpClient) {
            return new ApiRequest(config('api.resources.order'), $httpClient);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
