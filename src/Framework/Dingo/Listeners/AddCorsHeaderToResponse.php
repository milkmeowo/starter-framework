<?php
/**
 * AddCorsHeaderToResponse.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Dingo\Listeners;

use Dingo\Api\Event\ResponseWasMorphed;

class AddCorsHeaderToResponse
{
    public function handle(ResponseWasMorphed $event)
    {
        $headers = $event->response->headers;
        $headers->set('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
        $headers->set('Access-Control-Max-Age', 60);
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, HEAD, POST, PUT, DELETE');
    }
}
