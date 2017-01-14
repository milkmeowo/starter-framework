<?php

namespace Milkmeowo\Framework\Base\Providers;

use Clockwork\Support\Lumen\ClockworkMiddleware;
use Clockwork\Support\Lumen\ClockworkServiceProvider;
use Dingo\Api\Provider\LumenServiceProvider as DingoLumen;
use Barryvdh\Cors\LumenServiceProvider as CorsServiceProvider;
use Dingo\Api\Http\Middleware\Request as DingoMiddlewareRequest;
use Milkmeowo\Framework\Dingo\Providers\LumenEventsServiceProvider as DingoEvents;
use Dusterio\LumenPassport\PassportServiceProvider as LumenPassportServiceProvider;
use Milkmeowo\Framework\Dingo\Providers\ExceptionHandlerServiceProvider as DingoExceptionHandler;

class LumenServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->bootConfigure();

        $this->bootMiddleware();
    }

    protected function bootConfigure()
    {
        // l5-repository
        $this->app->configure('repository');

        // cors
        $this->app->configure('cors');
    }

    protected function bootMiddleware()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->middleware([
                'clockwork' => ClockworkMiddleware::class,
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

        $this->registerRepository();

        $this->registerDingo();

        $this->registerPassport();

        $this->registerClockwork();

        $this->registerCors();
    }

    protected function registerClockwork()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(ClockworkServiceProvider::class);
        }
    }

    protected function registerRepository()
    {
        // l5-repository validator
        $this->app->bind('Symfony\Component\Translation\TranslatorInterface', function ($app) {
            return $app['translator'];
        });
    }

    protected function registerDingo()
    {
        // dingo api
        $this->app->register(DingoLumen::class);
        $this->app->register(DingoExceptionHandler::class);
        $this->app->register(DingoEvents::class);
    }

    protected function registerPassport()
    {
        // lumen passport support
        $this->app->register(LumenPassportServiceProvider::class);
    }

    protected function registerCors()
    {
        $this->app->register(CorsServiceProvider::class);
    }
}
