<?php

namespace Milkmeowo\Framework\Base\Api\Middleware;

use Closure;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws HttpException
     */
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);

            // Was an exception thrown? If so and available catch in our middleware
            if (isset($response->exception) && $response->exception) {
                throw $response->exception;
            }

            return $response;
        } catch (AuthorizationException $e) {
            throw new AccessDeniedHttpException($e->getMessage());
        } catch (OAuthServerException $e) {
            $message = env('API_DEBUG') ? $e->getMessage() : null;
            throw new HttpException($e->getHttpStatusCode(), $message, $e, $e->getHttpHeaders());
        } catch (HttpResponseException $e) {
            $message = env('API_DEBUG') ? $e->getMessage() : null;
            throw new HttpException($e->getResponse()->getStatusCode(), $message, $e);
        } catch (AuthenticationException $e) {
            throw new UnauthorizedHttpException(null, $e->getMessage(), $e);
        } catch (ValidatorException $e) {
            $messageBag = $e->getMessageBag();
            throw new ResourceException($messageBag->first(), $messageBag->all());
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('No resources found.');
        }
    }
}
