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

            $header = '';
            if (isset($links['next'])) {
                $header .= '<'.$links['next'].'>; rel="next"';
            }

            if (isset($links['previous'])) {
                $header .= ', <'.$links['previous'].'>; rel="prev"';
            }

            $event->response->headers->set(
                'link',
                $header
            );
        }
    }
}
