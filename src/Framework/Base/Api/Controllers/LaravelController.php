<?php

namespace Milkmeowo\Framework\Base\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Milkmeowo\Framework\Base\Validation\ValidatesRequests;

abstract class LaravelController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /* Dingo API Helper */
    use DingoHelper;
}
