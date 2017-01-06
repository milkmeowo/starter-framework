<?php

namespace Milkmeowo\Framework\Dingo\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Illuminate\Routing\Controller as BaseController;

abstract class LaravelController extends BaseController
{
    /* Dingo API Helper */
    use DingoHelper;
}
