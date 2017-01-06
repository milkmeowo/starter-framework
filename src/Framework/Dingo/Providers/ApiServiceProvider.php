<?php
/**
 * ApiServiceProvider.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Dingo\Providers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $handler = app('Dingo\Api\Exception\Handler');
        $handler->register(function (AuthorizationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage());
        });
    }
}
