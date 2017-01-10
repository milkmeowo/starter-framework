<?php

namespace Milkmeowo\Framework\Base\Providers;

use Dingo\Api\Provider\LaravelServiceProvider as DingoLaravel;
use Milkmeowo\Framework\Dingo\Providers\ApiServiceProvider as DingoApi;
use Milkmeowo\Framework\Dingo\Providers\LaravelEventsServiceProvider as DingoEvents;

class LaravelServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();

        // dingo api
        $this->app->register(DingoLaravel::class);
        $this->app->register(DingoApi::class);
        $this->app->register(DingoEvents::class);
    }
}
