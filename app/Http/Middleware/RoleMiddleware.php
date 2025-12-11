<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Cek apakah user memiliki salah satu role yang diizinkan (case-insensitive, trimmed)
        $userRole = trim(strtolower($user->role ?? ''));
        $allowed = array_map(function($r) { return trim(strtolower($r)); }, $roles);

        if (!in_array($userRole, $allowed)) {
            abort(403, 'Anda tidak memiliki hak untuk mengakses halaman ini!');
        }

        return $next($request);
    }
}
