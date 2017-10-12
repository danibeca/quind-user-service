<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Response as IlluResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Utils\Helpers\ResponseHelper;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{

    use ResponseHelper;

    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];


    public function render($request, Exception $e)
    {
        if ($e instanceof BadRequestHttpException)
        {
            return $this->respondBadRequest($e->getMessage());
        }

        return $this->renderOtherSecurityExceptions($request, $e);
    }


    public function renderOtherSecurityExceptions($request, Exception $e)
    {
        if ($e instanceof UnauthorizedHttpException)
        {
            return $this->respondUnauthenticated($e->getMessage());
        }

        if ($e instanceof MethodNotAllowedHttpException)
        {
            return $this->respondMethodNotAllowed();
        }

        return $this->renderNotFoundExceptions($request, $e);
    }

    public function renderNotFoundExceptions($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException)
        {
            return $this->respondNotFound('Resource not found');
        }

        if ($e instanceof NotFoundHttpException)
        {
            return $this->respondNotFound();
        }

        return $this->renderExternalServerExceptions($request, $e);
    }

    public function renderExternalServerExceptions($request, Exception $e)
    {
        if ($e instanceof ServiceUnavailableHttpException)
        {
            return $this->setStatusCode(IlluResponse::HTTP_SERVICE_UNAVAILABLE)->respondWithError("Service unavailable");
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->respondUnauthenticated();
    }
}