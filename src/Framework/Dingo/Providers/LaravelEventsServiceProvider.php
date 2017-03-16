<?php
/**
 * LaravelEventsServiceProvider.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Dingo\Providers;

use Milkmeowo\Framework\Dingo\Listeners\AddPaginationLinksToResponse;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class LaravelEventsServiceProvider extends ServiceProvider
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
