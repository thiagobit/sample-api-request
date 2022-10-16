<?php

namespace App\Providers;

use App\Models\Request;
use App\Repositories\RequestRepositoryEloquent;
use App\Services\RequestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $repository = new RequestRepositoryEloquent(new Request());
        $httpClient = \Illuminate\Support\Facades\Http::baseUrl(config('api.url'));

        $this->app->singleton(
            'product-api',
            function () use ($repository, $httpClient) {
                return new RequestService(
                    config('api.resources.product'),
                    $repository,
                    $httpClient
                );
            }
        );

        $this->app->singleton(
            'order-api',
            function () use ($repository, $httpClient) {
                return new RequestService(
                    config('api.resources.order'),
                    $repository,
                    $httpClient
                );
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
