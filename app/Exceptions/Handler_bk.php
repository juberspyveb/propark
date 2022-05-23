<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        #return parent::render($request, $exception);
        if ($request->is('api*')) {
            $statusCode= Response::HTTP_UNAUTHORIZED;
            if($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException ){
                $response = [
                    'success' => false,
                    'status' => $statusCode,
                    'message' => $exception->getMessage(),
                ];
                return response()->json($response, $statusCode);
            }else if($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException ){
                $response = [
                    'success' => false,
                    'status' => $statusCode,
                    'message' => $exception->getMessage(),
                ];
                return response()->json($response, $statusCode);
            }else if($exception instanceof JWTException ){
                $response = [
                    'success' => false,
                    'status' => $statusCode,
                    'message' => $exception->getMessage(),
                ];
                return response()->json($response, $statusCode);
            }else if($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException){
                $response = [
                    'success' => false,
                    'status' => $statusCode,
                    'message' => $exception->getMessage(),
                ];
                return response()->json($response, $statusCode);
                #return response()->json(["error" => $exception->getMessage()], $exception->getStatusCode());
            }else if($exception instanceof QueryException){
                $response = [
                    'success' => false,
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    #'error' => "Database Query failed",
                    'message' => "Something went wrong [".$exception->getMessage()."]",
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }else if($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException){
                $response = [
                    'success' => false,
                    'status' => $exception->getStatusCode(),
                    'message' => "URL Not found",
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }else{
                $response = [
                    'success' => false,
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "Something went wrong",
                    //'message' => "Something went wrong [".$exception->getMessage()."]",
                ];
                return response()->json($response, Response::HTTP_INTERNAL_SERVER_ERROR);
            }



            /*if(@$exception->getStatusCode() == Response::HTTP_NOT_FOUND){
                $response = [
                    'success' => false,
                    'status' => $exception->getStatusCode(),
                    'error' => Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED],
                ];
                return response()->json($response, $exception->getStatusCode());
            }else{
                $response = [
                    'success' => false,
                    'status' => $statusCode,
                    'error' => "Something went wrong [".$exception->getMessage()."]",
                ];
                return response()->json($response, $statusCode);
            }*/
        }else{
            if ($request->wantsJson()) {
                $response = [
                    'success' => false,
                    'errors' => [
                        'status' => 403,
                        'message' => 'Unauthenticated',
                    ]
                ];
                return response()->json($response, 200);
            }
            return parent::render($request, $exception);
        }

        /*if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return abort('503');
        }
        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
            return response()->json(["message" => $exception->getMessage()], 401);
        }
        $this->renderable(function(TokenInvalidException $e, $request){
            return Response::json(['error'=>'Invalid token'],401);
        });
        $this->renderable(function (TokenExpiredException $e, $request) {
            return Response::json(['error'=>'Token has Expired'],401);
        });

        $this->renderable(function (JWTException $e, $request) {
            return Response::json(['error'=>'Token not parsed'],401);
        });

        return parent::render($request, $exception);*/
    }
}
