<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
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
     * @return JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        /*if ($validator->fails()) {
           if($request->ajax())
           {
               return response()->json(array(
                   'success' => false,
                   'message' => 'There are incorect values in the form!',
                   'errors' => $validator->getMessageBag()->toArray()
               ), 422);
           }
           $this->throwValidationException(
               $request, $validator
           );
       }
       */
        if(($request->ajax() && !$request->pjax()) || $request->wantsJson()) {
            if($exception instanceof ValidationException) {
                return new JsonResponse([
                    'success' => false,
                    'errors' => Arr::collapse($exception->errors()),
                    'message' => $exception->getMessage()
                ], 422);
            }
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage()
            ], 422);
        }
        return parent::render($request, $exception);
    }
}
