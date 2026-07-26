<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckSchoolApproval;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\CheckTeacherPermission;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Exceptions\TooManyRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
            'subscription' => CheckSubscription::class,
            'teacher.permission' => CheckTeacherPermission::class,
            'school.approval' => CheckSchoolApproval::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport([ValidationException::class]);

        $exceptions->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Record not found.'], 404);
            }

            return back()->withInput()->with('error', 'The requested record was not found or may have been removed.');
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not found.'], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $exceptions->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 403) {
                return null;
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            return response()->view('errors.403', [], 403);
        });

        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Validation failed.', 'errors' => $e->errors()], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        });

        $exceptions->renderable(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'CSRF token mismatch.'], 419);
            }

            return response()->view('errors.419', [], 419);
        });

        $exceptions->renderable(function (TooManyRequestsException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Too many requests. Please try again later.'], 429);
            }

            return response()->view('errors.429', [], 429);
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            report($e);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'An unexpected error occurred.'], 500);
            }

            return response()->view('errors.500', [], 500);
        });
    })->create();
