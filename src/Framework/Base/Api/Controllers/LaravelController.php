<?php

namespace Milkmeowo\Framework\Base\Api\Controllers;

use Dingo\Api\Routing\Helpers as DingoHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Milkmeowo\Framework\Base\Validation\ValidatesRequests;

abstract class LaravelController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /* Dingo API Helper */
    use DingoHelper;

    /**
     * LaravelController constructor.
     */
    public function __construct()
    {
        $this->middleware('api.access');
    }
}
