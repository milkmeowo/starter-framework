<?php

namespace Milkmeowo\Framework\Foundation\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Milkmeowo\Foundation\Validation\ValidatesRequests;

abstract class LaravelController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /* Dingo API Helper */
    use DingoHelper;
}
