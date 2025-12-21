<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse|View
    {
        $request->authenticate();

        $currentSessionId = session()->getId();
        $userId = auth()->id();

        // Cek apakah user punya session active lain di tabel sessions
        $existingSession = DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $currentSessionId)
            ->first();

        if ($existingSession) {
            // Logout dulu agar session Laravel bersih
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Simpan data pending login ke session BARU
            session([
                'pending_login_user_id' => $userId,
                'pending_login_device' => $request->userAgent(),
                'pending_login_old_session_id' => $existingSession->id,
            ]);

            return redirect()->route('login');
        }

        // Jika tidak ada session lain maka login normal
        $request->session()->regenerate();

        UserSession::updateOrCreate(
            ['user_id' => $userId],
            [
                'user_id' => $userId,
                'device' => $request->userAgent(),
                'session_id' => session()->getId(),
                'last_seen' => now(),
            ]
        );

        DB::table('sessions')
            ->where('id', session()->getId())
            ->update(['user_id' => $userId]);

        return redirect()->intended(route('dashboard'));
    }
    public function destroy(): RedirectResponse
    {
        UserSession::where('session_id', session()->getId())->delete();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }
}
