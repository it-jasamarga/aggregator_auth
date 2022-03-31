<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
class Handler extends ExceptionHandler
{   
    use ApiResponser;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    { 
      dd($exception);
      if ($request->wantsJson()) {
        if ($exception instanceof QueryException) {
          $errorCode = $exception->errorInfo[1];
          $errorInfo = $exception->errorInfo[2];

          if ($errorCode == 1451) {
            return $this->errorResponse('Cannot remove this resource permanently. It is related with any other resource', 409);
          }
          if ($errorCode == 1048) {
            return $this->errorResponse('Unexpected Exception. Try later', 500);
          }

          if ($errorCode == 1062) {
            return $this->errorResponse($errorInfo, 500);
          }

          if ($errorCode == 1364) {
            return $this->errorResponse($errorInfo, 500);
          }

          if ($errorCode == 500) {
            return $this->errorResponse($errorInfo, 500);
          }

        }

        if ($exception instanceof ModelNotFoundException) {
          $modelName = strtolower(class_basename($exception->getModel()));

          return $this->errorResponse("Does not exists any {$modelName} with the specified identificator", 404);
        }

        if ($exception instanceof AuthenticationException) {
          return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
          return $this->errorResponse($exception->getMessage(), 403);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
          return $this->errorResponse('The specified method for the request is invalid', 405);
        }

        if ($exception instanceof NotFoundHttpException) {
          return $this->errorResponse('The specified URL cannot be found', 404);
        }

        if ($exception instanceof HttpException) {
          return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof ValidationException) {
          return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof TokenMismatchException) {
          return redirect()->back()->withInput($request->input());
        }

        if ($exception instanceof \ErrorException) {
          return $this->errorResponse($exception->getMessage(), 500);
        }

        if ($exception instanceof BindingResolutionException) {
          // echo $exception->getMessage();
          return $this->errorResponse($exception->getMessage(), 500);
        }
      }
      return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
      if ($this->isFrontend($request)) {
        return redirect()->guest('login');
      }

      return $this->errorResponse('Unauthenticated.', 401);
    }

    protected function noFound($request, NotFoundHttpException $exception)
    {
      if ($this->isFrontend($request)) {
        return redirect()->guest('login');
      }

      return $this->errorResponse('Unauthenticated.', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
      $errors = $e->validator->errors()->getMessages();

      if ($this->isFrontend($request)) {
        return $request->ajax() ? response()->json($errors, 422) : redirect()
          ->back()
          ->withInput($request->input())
          ->withErrors($errors);
      }

      return $this->errorResponse($errors, 422);
    }

    private function isFrontend($request)
    {
      return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
