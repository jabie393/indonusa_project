<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        try {
            // If it's an HTTP exception with 403 status, log details for debugging
            if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
                try {
                    $user = auth()->user();
                    $userId = $user ? $user->id : null;
                    $userRole = $user ? ($user->role ?? null) : null;
                } catch (Throwable $e) {
                    $userId = null;
                    $userRole = null;
                }

                Log::warning('403 Forbidden encountered', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => $userId,
                    'user_role' => $userRole,
                    'ip' => $request->ip(),
                    'message' => $exception->getMessage(),
                ]);
            }
        } catch (Throwable $e) {
            // swallow logging exceptions to avoid masking the original error
        }

        return parent::render($request, $exception);
    }
}
