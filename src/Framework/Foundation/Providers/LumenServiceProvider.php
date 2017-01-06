<?php

namespace Milkmeowo\Framework\Foundation\Providers;

use Dingo\Api\Provider\LumenServiceProvider as DingoLumenServiceProvider;
use Milkmeowo\Framework\Repository\Providers\RepositoryServiceProvider;
use Tymon\JWTAuth\Providers\LumenServiceProvider as JWTLumenServiceProvider;

class LumenServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();

        // dingo api
        $this->app->register(DingoLumenServiceProvider::class);

        // Tymon JWT
        $this->app->register(JWTLumenServiceProvider::class);
    }
}