<?php
/**
 * ApiServiceProvider.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Dingo\Providers;

use Illuminate\Support\ServiceProvider;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionHandlerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $handler = app('Dingo\Api\Exception\Handler');

        $handler->register(function (AuthorizationException $exception) {
            throw new AccessDeniedHttpException($exception->getMessage());
        });

        $handler->register(function (OAuthServerException $e) {
            $message = env('API_DEBUG') ? $e->getMessage() : null;
            throw new HttpException($e->getHttpStatusCode(), $message, $e, $e->getHttpHeaders());
        });

        $handler->register(function (HttpResponseException $e) {
            $message = env('API_DEBUG') ? $e->getMessage() : null;
            throw new HttpException($e->getResponse()->getStatusCode(), $message, $e);
        });

        $handler->register(function (AuthenticationException $e) {
            throw new UnauthorizedHttpException(null, $e->getMessage(), $e);
        });

        $handler->register(function (ValidatorException $e) {
            $messageBag = $e->getMessageBag();
            throw new ResourceException($messageBag->first(), $messageBag->all());
        });

        $handler->register(function (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No resources found.');
        });
    }
}
