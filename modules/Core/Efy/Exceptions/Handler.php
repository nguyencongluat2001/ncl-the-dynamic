<?php

namespace Modules\Core\Efy\Exceptions;


use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use ApiResponse;

class Handler extends ExceptionHandler
{
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
           return $this->renderApi($request, $exception);
        }
        
        return parent::render($request, $exception);
    }

    private function renderApi($request, $exception){

        $success = false;
        $status = 201;
        $file = $exception->getFile();
        $line = $exception->getLine();
        
        if ($exception instanceof ValidationException) {
            $message = $exception->validator->messages();
        }else{
            $message = $exception->getMessage();
        }
        
        return ApiResponse::json(
            \compact("success","message","file","line"),$status
        );
    }


}
