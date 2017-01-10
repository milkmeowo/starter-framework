<?php
/**
 * AddPaginationLinksToResponse.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Dingo\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

class AddPaginationLinksToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        if (isset($event->content['meta']['pagination'])) {
            $links = $event->content['meta']['pagination']['links'];

            $event->response->headers->set(
                'link',
                sprintf('<%s>; rel="next", <%s>; rel="prev"', $links['links']['next'], $links['links']['previous'])
            );
        }
    }
}
