<?php

namespace Modules\Core\Ncl\Exceptions;

use Throwable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ApiHander extends ExceptionHandler
{

    private $statusCode = 400;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if (strpos($request->url(), '/api/') !== false) {

            if ($exception instanceof ResponseExeption) {
                return $this->respondMessage($exception);
            }

            if ($exception instanceof ValidationException) {
                return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($exception->validator->messages());
            }

            if ($exception instanceof AuthenticationException) {
                $success = false;
                $message = "Unauthenticated";
                return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respond(\compact("success", "message"));
            }

            if ($exception instanceof Exception) {
                return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondException($exception);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * get the status code.
     *
     * @return statuscode
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * set the status code.
     *
     * @param [type] $statusCode [description]
     *
     * @return statuscode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondException($exception)
    {
        $success = false;
        $status = 201;
        $file = $exception->getFile();
        $line = $exception->getLine();

        if ($exception instanceof ValidationException) {
            $message = $exception->validator->messages();
        } else {
            $message = $exception->getMessage();
        }
        return $this->respond(\compact("success", "message", "file", "line"));
    }

    protected function respondWithError($message)
    {
        $success = false;
        return $this->respond(\compact("success", "message"));
    }

    /**
     * respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondMessage($exception)
    {
        $this->setStatusCode($exception->getCode());
        $success = false;
        if ($exception instanceof ValidationException) {
            $message = $exception->validator->messages();
        } else {
            $message = $exception->getMessage();
        }

        return $this->respond(\compact("success", "message"));
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }
}
