<?php

namespace Milkmeowo\Framework\Base\Providers;

use Dingo\Api\Provider\LaravelServiceProvider as DingoLaravel;
use Milkmeowo\Framework\Base\Api\Middleware\ApiAccessMiddleware;
use Milkmeowo\Framework\Dingo\Providers\ApiServiceProvider as DingoApi;
use Milkmeowo\Framework\Dingo\Providers\LaravelEventsServiceProvider as DingoEvents;

class LaravelServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->bootMiddleware();
    }

    public function register()
    {
        parent::register();

        $this->registerDingo();
    }

    protected function registerDingo()
    {
        // dingo api
        $this->app->register(DingoLaravel::class);
        $this->app->register(DingoApi::class);
        $this->app->register(DingoEvents::class);
    }

    protected function bootMiddleware()
    {
        $this->app['router']->middleware('api.access', ApiAccessMiddleware::class);
    }
}
