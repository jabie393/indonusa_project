<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    // Approve penawaran
    public function approve($id)
    {
        $order = Order::where('request_order_id', $id)->firstOrFail();
        $order->update([
            'status'        => 'approved_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at'   => now(),
        ]);
        return redirect()->back()->with('success', 'Penawaran berhasil disetujui');
    }

    // Reject penawaran
     public function supervisorReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $order = Order::where('request_order_id', $id)->first();
        if (!$order) {
            return back()->withErrors('Order tidak ditemukan.');
        }

        $order->update([
            'status'        => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at'   => now(),
            'reason'        => $request->reason,
        ]);

        // Simpan reason ke request_orders table agar sales bisa lihat di halaman show
        $requestOrder = \App\Models\RequestOrder::find($id);
        if ($requestOrder) {
            $requestOrder->update(['reason' => $request->reason]);
        }

        return redirect()->back()->with('success', 'Request order berhasil ditolak.');
    }
}
