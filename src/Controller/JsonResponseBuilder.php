<?php

namespace App\Controller;


use App\Exception\AlreadyExistException;
use App\Exception\ArgumentNotValidException;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonResponseBuilder
{
    private $data = null;
    private $status = Response::HTTP_OK;
    private $headers = array();

    //private $json = false;


    public function data($data): JsonResponseBuilder
    {
        $this->data = $data;
        return $this;
    }

    public function status(int $status): JsonResponseBuilder
    {
        $this->status = $status;
        return $this;
    }

    public function headers(array $headers): JsonResponseBuilder
    {
        $this->headers = $headers;
        return $this;
    }

    public function build(): JsonResponse
    {
        return new JsonResponse($this->data, $this->status, $this->headers);
    }


    public static function builder(int $status = Response::HTTP_OK): JsonResponseBuilder
    {
        return (new JsonResponseBuilder())->status($status);
    }

    /**
     * Genera una respuesta 200 - Ok
     * @param $data
     * @return JsonResponseBuilder
     */
    public static function success200Ok($data): JsonResponseBuilder
    {
        return self::builder(Response::HTTP_OK)->data($data);
    }

    /**
     * Genera una respuesta 201 - Created
     * @param $data
     * @return JsonResponseBuilder
     */
    public static function success201Created($data): JsonResponseBuilder
    {
        return self::builder(Response::HTTP_CREATED)->data($data);
    }

    /**
     * Genera una respuesta 204 - No Content
     * @return JsonResponseBuilder
     */
    public static function success204Deleted(): JsonResponseBuilder
    {
        return self::builder(Response::HTTP_NO_CONTENT);
    }

    /**
     * Genera una respuesta 400 - Bad Request
     * @param string|null $secondaryMessage
     * @return JsonResponseBuilder
     */
    public static function error400BadRequest(string $secondaryMessage = null): JsonResponseBuilder
    {
        return self::error(Response::HTTP_BAD_REQUEST,
            'Bad Request' . (empty($secondaryMessage) ? '' : ' ' . $secondaryMessage));
    }

    /**
     * Genera una respuesta 404 - Not Found
     * @return JsonResponseBuilder
     */
    public static function error404NotFound(): JsonResponseBuilder
    {
        return self::error(Response::HTTP_NOT_FOUND,
            'Not Found'
        );
    }

    /**
     * Genera una respuesta 422 - Unprocessable Entity
     * @param string|null $secondaryMessage
     * @return JsonResponseBuilder
     */
    public static function error422UnprocessableEntity(string $secondaryMessage = null): JsonResponseBuilder
    {
        return self::error(Response::HTTP_UNPROCESSABLE_ENTITY,
            'Unprocessable Entity' . (empty($secondaryMessage) ? '' : ' ' . $secondaryMessage)
        );
    }

    /**
     * Genera una respuesta 500 - Internal Server Error
     * @param string|null $secondaryMessage
     * @return JsonResponseBuilder
     */
    public static function error500InternalServerError(string $secondaryMessage = null): JsonResponseBuilder
    {
        return self::error(Response::HTTP_INTERNAL_SERVER_ERROR,
            'Internal Server Error.' . (empty($secondaryMessage) ? '' : ' ' . $secondaryMessage)
        );
    }

    /**
     * @param int $statusCode
     * @param string $message
     *
     * @return JsonResponseBuilder
     */
    private static function error(int $statusCode, string $message): JsonResponseBuilder
    {
        return self::builder($statusCode)->data(
            [
                'message' => [
                    'code' => $statusCode,
                    'message' => $message
                ]
            ],
            $statusCode
        );
    }

    /**
     * Genera una respuesta a partir de una Exception
     * @param \Exception $e
     * @return JsonResponseBuilder
     */
    public static function fromException(\Exception $e): JsonResponseBuilder
    {
        $message = $e->getMessage();
        if ($e instanceof ArgumentNotValidException) {
            return self::error422UnprocessableEntity($message);
        }

        if ($e instanceof AlreadyExistException) {
            return self::error400BadRequest($message);
        }

        if ($e instanceof NotFoundException) {
            return self::error404NotFound();
        }

        if ($e instanceof HttpException) {
            return self::error($e->getStatusCode(), $message);
        }

        return self::error500InternalServerError($message);
    }
}