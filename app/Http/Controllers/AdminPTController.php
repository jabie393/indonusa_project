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
        // List custom penawaran (status = 'sent') and RequestOrders that require supervisor approval
        $penawarans = CustomPenawaran::where('status', 'sent')
            ->with(['items', 'sales'])
            ->get();

        $requestOrders = \App\Models\RequestOrder::where('status', 'pending_approval')
            ->with(['items', 'sales'])
            ->get();

        // Tag each item with a type so view can differentiate
        $penawarans->each(function($p) { $p->offer_type = 'custom'; });
        $requestOrders->each(function($r) { $r->offer_type = 'request_order'; });

        // Merge and sort by created_at desc
        $all = $penawarans->concat($requestOrders)->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $currentItems = $all->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.sent-penawaran.sent_penawaran', ['penawarans' => $paginator]);
    }

    public function history()
    {
        $orders = Order::where('status', '!=', 'pending')->with(['items.barang','sales','supervisor','warehouse'])->latest()->paginate(10);
        return view('admin.incoming-orders.history', compact('orders'));
    }
}
