<?php
/**
 * AddLastModifiedToResponse.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Dingo\Listeners;

use Carbon\Carbon;
use Dingo\Api\Event\ResponseWasMorphed;

class AddLastModifiedToResponse
{
    public function handel(ResponseWasMorphed $event)
    {
        if ($event->content) {
            $event->response->header('Last-Modified', Carbon::now()->toRfc2822String());
        }
    }
}
