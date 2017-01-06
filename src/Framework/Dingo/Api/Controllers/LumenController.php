<?php

namespace Milkmeowo\Framework\Dingo\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Laravel\Lumen\Routing\Controller;

abstract class LumenController extends Controller
{
    use DingoHelper;
}