<?php

namespace Milkmeowo\Framework\Base\Providers;

use Dingo\Api\Provider\LumenServiceProvider as DingoLumenServiceProvider;

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
        $this->app->register(DingoLumenServiceProvider::class);
    }
}
