<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class AdminPTController extends Controller
{

    public function dashboard()
    {
        $pending = Order::where('status', 'pending')->count();
        $sent = Order::where('status', 'sent_to_warehouse')->count();
        $history = Order::where('status', '!=', 'pending')->count();
        $orders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('pending','sent','history','orders'));
    }

    public function incoming()
    {
        $orders = Order::where('status','pending')->with(['items.barang','sales'])->get();
        return view('admin.orders.incoming', compact('orders'));


    }

    public function show($id)
    {
        $order = Order::with(['items.barang','sales'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'sent_to_warehouse'; // diteruskan ke warehouse sesuai flowchart
        $order->pt_id = Auth::id();
        $order->save();

        return redirect()->route('admin.incoming')->with('success', 'Order disetujui dan diteruskan ke Admin Warehouse.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        $order->status = 'rejected_pt';
        $order->pt_id = Auth::id();
        $order->reason = $request->reason;
        $order->save();

        return redirect()->route('admin.incoming')->with('success', 'Order ditolak dan dikembalikan ke Admin Sales.');
    }

    public function history()
    {
        $orders = Order::where('status', '!=', 'pending')->with(['items.barang','sales','pt','warehouse'])->latest()->paginate(10);
        return view('admin.orders.history', compact('orders'));
    }
}
