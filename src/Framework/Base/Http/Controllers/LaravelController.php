<?php

namespace Milkmeowo\Framework\Base\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class LaravelController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
