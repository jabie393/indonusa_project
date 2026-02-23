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
    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string']);
        $order = Order::where('request_order_id', $id)->firstOrFail();
        $order->update([
            'status'        => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at'   => now(),
            'reason'        => $request->reason,
        ]);
        return redirect()->back()->with('success', 'Penawaran berhasil ditolak');
    }
}
