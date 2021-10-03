<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\BaseException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception) && app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // jika debug di matikan maka trace error tidak akan di tampilkan di di render response eror
        if (!config('app.debug')) {
            if ($exception instanceof MethodNotAllowedHttpException) {
                $exception = new BaseException(405, 'Method Not Allowed');
            } elseif ($exception instanceof ModelNotFoundException) {
                $exception = new BaseException(404, 'Data Not Found', [
                    'trace' => $exception->getTraceAsString()
                ]);
            } elseif ($exception instanceof NotFoundHttpException) {
                $exception = new BaseException(404, 'Route Not Found');
            } elseif ($exception instanceof ServerException) {
                $exception = new BaseException(500, 'Terjadi kesalahan. Mohon ulangi kembali');
            }
            // else {
            //     $exception = new BaseException(500, "Server error");
            // }
        }
        if ($exception instanceof ValidationException) {
            $exception = new BaseException(
                200,
                $exception->validator->errors()->first(),
                null,
                [
                    'status' => false,
                    'data' => [
                        'message' => $exception->validator->errors()->first()
                    ],
                ]
            );
        }

        return parent::render($request, $exception);
    }
}