<?php

namespace Milkmeowo\Framework\Base\Providers;

use Dingo\Api\Provider\LaravelServiceProvider as DingoLaravel;
use Milkmeowo\Framework\Dingo\Providers\ExceptionHandlerServiceProvider as DingoExceptionHandler;
use Milkmeowo\Framework\Dingo\Providers\LaravelEventsServiceProvider as DingoEvents;

class LaravelServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->bootMiddleware();
    }

    protected function bootMiddleware()
    {
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
        $this->app->register(DingoExceptionHandler::class);
        $this->app->register(DingoEvents::class);
    }
}
