<?php

namespace App\Utils\Helpers;

use Illuminate\Http\Response as IlluResponse;
use Illuminate\Support\Facades\Response;

trait ResponseHelper {

    protected $statusCode = IlluResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(IlluResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    public function respondInternalErorr($message = 'Internal Error')
    {
        return $this->setStatusCode(IlluResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    public function respondMethodNotAllowed($message = 'Method Not Allowed')
    {
        return $this->setStatusCode(IlluResponse::HTTP_METHOD_NOT_ALLOWED)->respondWithError($message);
    }

    public function respondUnauthenticated($message = 'Unauthenticated')
    {
        return $this->setStatusCode(IlluResponse::HTTP_UNAUTHORIZED)->respondWithError($message);
    }

    public function respondBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(IlluResponse::HTTP_BAD_REQUEST)->respondWithError($message);
    }

    public function respondResourceCreated($message = 'Created')
    {
        return $this->setStatusCode(IlluResponse::HTTP_CREATED)->respond($message);
    }

    public function respondResourceRestored($message = 'User restore because it already exists')
    {
        return $this->setStatusCode(IlluResponse::HTTP_CREATED)->respond($message);
    }

    public function respondResourceDeleted($message = 'Deleted')
    {
        return $this->respond($message);
    }

    public function respondResourceConflict($message = 'Conflict')
    {
        return $this->setStatusCode(IlluResponse::HTTP_CONFLICT)->respond($message);
    }

    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers, JSON_UNESCAPED_UNICODE);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'statusCode' => $this->getStatusCode()
            ]
        ]);
    }
}