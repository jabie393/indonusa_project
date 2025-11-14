<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\UserForceLogoutEvent;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConfirmLoginController extends Controller
{
    public function show()
    {
        if (!session('pending_login_user_id')) {
            return redirect('/login');
        }
        // Return modal config as JSON so the frontend can render a SweetAlert modal
        return response()->json([
            'title' => 'Sesi Aktif Ditemukan',
            'html' => 'Akun kamu sudah login di perangkat lain.<br>Apakah kamu ingin melanjutkan dan menendang sesi lama?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Lanjutkan',
            'cancelButtonText' => 'Tunggu',
        ]);
    }

    public function continue(Request $request)
    {
        $userId = session('pending_login_user_id');

        if (!$userId) {
            return redirect('/login');
        }

        // Ambil session lama sebelum dihapus
        $oldSessions = DB::table('sessions')
            ->where('user_id', $userId)
            ->pluck('id')
            ->toArray();

        // Trigger event supaya device lama logout realtime
        foreach ($oldSessions as $oldSessionId) {
            broadcast(new UserForceLogoutEvent($oldSessionId));
        }

        // Hapus semua session lama
        DB::table('sessions')->where('user_id', $userId)->delete();

        // Login user di perangkat baru
        Auth::loginUsingId($userId);
        $request->session()->regenerate();

        // Simpan ke tabel sessions
        DB::table('sessions')
            ->where('id', session()->getId())
            ->update(['user_id' => $userId]);

        // Update custom user_sessions
        UserSession::updateOrCreate(
            ['user_id' => $userId],
            [
                'session_id' => session()->getId(),
                'device' => $request->userAgent(),
                'last_seen' => now(),
            ]
        );

        session()->forget('pending_login_user_id');
        session()->forget('pending_login_device');

        // Return JSON so the frontend can redirect without relying on a full-page
        return response()->json([
            'success' => true,
            'redirect' => route('dashboard'),
        ]);
    }

    public function cancel()
    {
        session()->forget('pending_login_user_id');
        session()->forget('pending_login_device');
        return response()->json([
            'success' => true,
            'redirect' => route('login'),
            'message' => 'Login dibatalkan.'
        ]);
    }
}
