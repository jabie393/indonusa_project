<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Events\UserLoggedInElsewhere;
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
            'html' => 'Akun kamu sedang login di perangkat lain.<br>Keluarkan sesi di perangkat lain dan login di sini?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Ya, Lanjutkan',
            'cancelButtonText' => 'Batal',
        ]);
    }

    public function continue(Request $request)
    {
        $userId = session('pending_login_user_id');

        if (!$userId) {
            return response()->json(['success' => false, 'redirect' => route('login')], 403);
        }

        // Trigger event supaya device lama logout realtime
        // Ini dipanggil saat Device B klik "Ya, Lanjutkan"
        broadcast(new UserLoggedInElsewhere($userId, $request->userAgent()));

        // Hapus SEMUA session lama user ini di database (Device A otomatis tertendang secara teknis)
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
