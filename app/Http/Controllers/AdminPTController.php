<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CustomPenawaran;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderStatusUpdated;

class AdminPTController extends Controller
{

    // incoming() removed — Incoming Orders page for Supervisor is no longer used.

    public function show($id)
    {
        // The Incoming Orders "show" page has been removed for Supervisor.
        // Redirect to orders history to avoid rendering a removed view.
        return redirect()->route('orders.history')->with('info', 'Halaman detail Incoming Orders telah dihapus.');
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'sent_to_warehouse'; // diteruskan ke warehouse sesuai flowchart
        $order->supervisor_id = Auth::id();
        $order->save();

        // Memancarkan event setelah status berubah
        event(new OrderStatusUpdated($order->id));

        // Setelah approve, arahkan ke halaman sent penawaran/pengecekan diskon jika ada
        return redirect()->route('admin.sent_penawaran')->with('success', 'Order disetujui dan diteruskan ke Admin Warehouse.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        $order->status = 'rejected_supervisor';
        $order->supervisor_id = Auth::id();
        $order->reason = $request->reason;
        $order->save();

        return redirect()->route('orders.history')->with('success', 'Order ditolak dan dikembalikan ke Admin Sales.');
    }

    public function sentPenawaran()
    {
        // List custom penawaran from Sales that were sent for supervisor approval (status = 'sent')
        $penawarans = CustomPenawaran::where('status', 'sent')
            ->with(['items', 'sales'])
            ->latest()
            ->paginate(10);

        return view('admin.sent-penawaran.sent_penawaran', compact('penawarans'));
    }

    public function history()
    {
        $orders = Order::where('status', '!=', 'pending')->with(['items.barang','sales','supervisor','warehouse'])->latest()->paginate(10);
        return view('admin.incoming-orders.history', compact('orders'));
    }
}
