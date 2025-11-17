<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class AdminPTController extends Controller
{

    public function incoming()
    {
        $orders = Order::where('status','pending')->with(['items.barang','sales'])->get();
        return view('admin.incoming-orders.incoming', compact('orders'));


    }

    public function show($id)
    {
        $order = Order::with(['items.barang','sales'])->findOrFail($id);
        return view('admin.incoming-orders.show', compact('order'));
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'sent_to_warehouse'; // diteruskan ke warehouse sesuai flowchart
        $order->supervisor_id = Auth::id();
        $order->save();

        // Setelah approve, arahkan ke halaman approved orders agar admin PT melihat daftar yang sudah dikirim ke warehouse
        return redirect()->route('admin.approved')->with('success', 'Order disetujui dan diteruskan ke Admin Warehouse.');
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

        return redirect()->route('admin.incoming')->with('success', 'Order ditolak dan dikembalikan ke Admin Sales.');
    }

    public function approved()
    {
        // Orders that have been approved by PT and sent to warehouse
        $orders = Order::where('status', 'sent_to_warehouse')
            ->with(['items.barang', 'sales', 'supervisor', 'warehouse'])
            ->latest()
            ->paginate(10);

        return view('admin.approved-order.approved_orders', compact('orders'));
    }

    public function history()
    {
        $orders = Order::where('status', '!=', 'pending')->with(['items.barang','sales','supervisor','warehouse'])->latest()->paginate(10);
        return view('admin.incoming-orders.history', compact('orders'));
    }
}
