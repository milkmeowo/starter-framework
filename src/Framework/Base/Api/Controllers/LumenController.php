<?php

namespace Milkmeowo\Framework\Base\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class LumenController extends BaseController
{
    use DingoHelper;
}
