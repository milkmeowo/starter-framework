<?php

namespace Milkmeowo\Framework\Dingo\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Milkmeowo\Framework\Dingo\Listeners\AddPaginationLinksToResponse;

class LumenEventsServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the Dingo.
     *
     * @var array
     */
    protected $listen = [
        'Dingo\Api\Event\ResponseWasMorphed' => [
            AddPaginationLinksToResponse::class,
        ],
    ];
}
