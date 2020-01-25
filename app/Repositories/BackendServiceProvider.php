<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            'App\Repositories\ProductRepositoryInterface',
            'App\Repositories\ProductRepository',
        );

        $this->app->bind(
            'App\Repositories\OrderRequestRepositoryInterface',
            'App\Repositories\OrderRequestRepository',
        );

        $this->app->bind(
            'App\Repositories\PaymentRepositoryInterface',
            'App\Repositories\PaymentRepository',
        );

        $this->app->bind(
            'App\Repositories\CategoryRepositoryInterface',
            'App\Repositories\CategoryRepository',
        );

        $this->app->bind(
            'App\Repositories\BrandRepositoryInterface',
            'App\Repositories\BrandRepository',
        );
    }
}
