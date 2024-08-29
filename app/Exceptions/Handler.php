<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if ($exception instanceof QueryException) {
            if ($exception->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => false,
                    'error' => 'Duplicate entry detected',
                    'message' => 'A record with the same value already exists.',
                ], 400);
            }
            return response()->json([
                'status' => false,
                'error' => 'Database error',
                'message' => 'An unexpected database error occurred.',
            ], 500);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'status' => false,
                'message' => 'Please fill all the required fields & None of the fields should be empty.',
                'error' => $exception->errors(),
            ], 422);
        }

        // Check if the exception is a MethodNotAllowedHttpException
        if ($exception instanceof MethodNotAllowedHttpException) {
            // Return a custom response for the 405 error
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 405);
        }

        // Handle NotFoundHttpException

        //api
        if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 404);
        }

        //web
        if ($exception instanceof NotFoundHttpException && !$request->wantsJson()) {
            $error = $exception->getMessage();
            return response()->view('custom_page.page_404', compact('error'));
        }

        if ($exception instanceof AuthenticationException || $exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token. Please provide a valid token.',
            ], 401);
        }

        if ($request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function handleApiException($request, Throwable $exception)
    {

        $status = $exception instanceof HttpException ? $exception->getStatusCode() : 500;
        $message = $status === 500 ? 'Something went wrong' : $exception->getMessage();

        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $exception->getMessage(),
        ], $status);

        // $status = 500;
        // $response = [
        //     'status' => false,
        //     'message' => $exception->getMessage(),
        // ];

        // return new JsonResponse($response, $status);
    }
}
