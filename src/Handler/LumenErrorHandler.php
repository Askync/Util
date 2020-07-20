<?php

namespace Askync\Utils\Handler;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Askync\Utils\Facades\AskyncResponse;
use Askync\Utils\Utils\ResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LumenErrorHandler extends ExceptionHandler
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
        ResponseException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if($exception->getPrevious() instanceof OResponseException) {
            $exception = $exception->getPrevious();
        }
        if($exception instanceof OResponseException) {
            $request->header('Accept' , 'application/json');
            $errors = $exception->errors;
            $code = $exception->statusCode;
            $message = $exception->getMessage();
            return AskyncResponse::fail( $code, $message, $errors );
        }

        if ( $request->expectsJson()) {
            $errors = [];
            $code = (method_exists($exception, 'getStatusCode'))?$exception->getStatusCode():501;
            $message = $exception->getMessage();

            if( $exception instanceof AuthenticationException ) {

                $code = 401;
                $message = $exception->getMessage();

            }
            if( isset($exception->validator) && is_array( $exception->validator->errors()->getMessages() ) ) {
                $code = 422;
                foreach($exception->validator->errors()->getMessages() as $key=>$val) {
                    $errors[$key] = $val;
                }
            }
            if($exception instanceof ResponseException) {
                $errors = $exception->errors;
                $code = $exception->statusCode;
                $message = $exception->getMessage();
            }
            if ($this->isDebugMode()) {
                $errors['debug'] = [
                    'exception' => get_class($exception),
                    'line' => $exception->getLine(),
                    'file' => basename( $exception->getFile() )
                ];
            }

            if ($exception instanceof NotFoundHttpException) {
                $errors = [ 'resource' => 'Resource not found' ];
                if($this->isDebugMode()) {
                    $errors['debug'] = [
                        'route' => $exception->getLine() === 229 ? 'Route not found' : 'Controller method not found',
                    ];
                }
                return AskyncResponse::fail( 404, 'Resource not found for this request', $errors );
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                $errors = [ 'request' => 'Server denied the request' ];
                if($this->isDebugMode()) {
                    $errors['debug'] = [
                        'method' => 'method not allowed',
                        'code' => 405,
                    ];
                }
                return AskyncResponse::fail( 405, 'Server cannot accept this request', $errors );
            }

            return AskyncResponse::fail( $code, $message, $errors );
        }



        return parent::render($request, $exception);
    }
    public function isDebugMode()
    {
        return (boolean) env('APP_DEBUG');
    }
}
