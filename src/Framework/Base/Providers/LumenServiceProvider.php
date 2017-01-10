<?php

namespace Milkmeowo\Framework\Base\Providers;

use Dingo\Api\Provider\LumenServiceProvider as DingoLumen;
use Milkmeowo\Framework\Dingo\Providers\ApiServiceProvider as DingoApi;
use Milkmeowo\Framework\Dingo\Providers\LumenEventsServiceProvider as DingoEvents;

class LumenServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        parent::boot();

        // l5-repository
        $this->app->configure('repository');
    }

    public function register()
    {
        parent::register();

        // l5-repository validator
        $this->app->bind('Symfony\Component\Translation\TranslatorInterface', function ($app) {
            return $app['translator'];
        });

        // dingo api
        $this->app->register(DingoLumen::class);
        $this->app->register(DingoApi::class);
        $this->app->register(DingoEvents::class);
    }
}
