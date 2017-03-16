<?php

namespace Milkmeowo\Framework\Base\Providers;

use Clockwork\Support\Laravel\ClockworkMiddleware;
use Clockwork\Support\Laravel\ClockworkServiceProvider;
use Barryvdh\Cors\ServiceProvider as CorsServiceProvider;
use Dingo\Api\Provider\LaravelServiceProvider as DingoLaravel;
use Dingo\Api\Http\Middleware\Request as DingoMiddlewareRequest;
use Milkmeowo\Framework\Dingo\Providers\LaravelEventsServiceProvider as DingoEvents;
use Milkmeowo\Framework\Dingo\Providers\ExceptionHandlerServiceProvider as DingoExceptionHandler;

class LaravelServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->bootMiddleware();
    }

    protected function bootMiddleware()
    {
        $httpKernel = app('Illuminate\Contracts\Http\Kernel');
        // Global middleware
        if ($this->app->environment() !== 'production') {
            $httpKernel->pushMiddleware([
                ClockworkMiddleware::class,
            ]);
            // Register into dingo api middleware
            $this->app[DingoMiddlewareRequest::class]->mergeMiddlewares([
                'clockwork' => ClockworkMiddleware::class,
            ]);
        }
    }

    public function register()
    {
        parent::register();

        $this->registerDingo();

        $this->registerClockwork();

        $this->registerCors();
    }

    protected function registerClockwork()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(ClockworkServiceProvider::class);
        }
    }

    protected function registerDingo()
    {
        // dingo api
        $this->app->register(DingoLaravel::class);
        $this->app->register(DingoExceptionHandler::class);
        $this->app->register(DingoEvents::class);
    }

    protected function registerCors()
    {
        $this->app->register(CorsServiceProvider::class);
    }
}
