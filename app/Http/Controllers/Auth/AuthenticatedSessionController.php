<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Events\UserForceLogoutEvent;
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

            // Simpan data pending login
            session([
                'pending_login_user_id' => $userId,
                'pending_login_device' => $request->userAgent(),
                'pending_login_old_session_id' => $existingSession->id,
            ]);

            // logout sementara
            Auth::logout();

            // Redirect back to login page; the login view will fetch /confirm-login and
            // show the SweetAlert modal when a pending login exists.
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
    // Memaksa logout session lama
    public function confirmForceLogin()
    {
        $oldSessionId = session('pending_login_old_session_id');
        $userId = session('pending_login_user_id');

        if (!$oldSessionId || !$userId) {
            return redirect('/login')->with('error', 'Tidak ada proses login yang tertunda.');
        }

        // Hapus session lama
        DB::table('sessions')->where('id', $oldSessionId)->delete();
        UserSession::where('session_id', $oldSessionId)->delete();

        // Kirim event ke Reverb lalu memaksa logout realtime di device lama
        broadcast(new UserForceLogoutEvent($oldSessionId));

        // Login ulang user
        Auth::loginUsingId($userId);
        request()->session()->regenerate();

        // Simpan sesi baru
        UserSession::updateOrCreate(
            ['user_id' => $userId],
            [
                'user_id' => $userId,
                'device' => request()->userAgent(),
                'session_id' => session()->getId(),
                'last_seen' => now(),
            ]
        );

        DB::table('sessions')
            ->where('id', session()->getId())
            ->update(['user_id' => $userId]);

        // Hapus temporary session
        session()->forget([
            'pending_login_old_session_id',
            'pending_login_user_id',
            'pending_login_device',
        ]);

        return redirect()->route('dashboard');
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
