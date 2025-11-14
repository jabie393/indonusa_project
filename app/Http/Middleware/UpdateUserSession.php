<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserSession;

class UpdateUserSession
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            UserSession::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'device' => $request->userAgent(),
                ],
                [
                    'last_seen' => now(),
                    'session_id' => session()->getId(),
                ]
            );
        }

        return $next($request);
    }
}
