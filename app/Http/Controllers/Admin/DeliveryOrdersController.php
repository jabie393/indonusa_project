<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DeliveryOrdersController extends Controller
{
    // Tampilkan daftar orders yang statusnya 'sent_to_warehouse'
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');

        // Baseline query: eager-load relations and filter by status
        $orders = Order::with(['supervisor', 'items.barang', 'customer', 'requestOrder.sales'])
            ->whereIn('status', ['sent_to_warehouse', 'approved_warehouse', 'rejected_warehouse'])
            ->orderBy('created_at', 'desc');

        if ($query) {
            $orders = $orders->where(function ($q) use ($query) {
                // If query looks numeric, allow id matching
                if (is_numeric($query)) {
                    $q->orWhere('id', $query);
                }

                // Search by order number (allow partial matches)
                $q->orWhere('order_number', 'like', "%{$query}%");

                // Search supervisor name (common column 'name'). If a 'username' column exists, include it.
                $q->orWhereHas('supervisor', function ($sq) use ($query) {
                    $sq->where('name', 'like', "%{$query}%");
                    if (Schema::hasColumn('users', 'username')) {
                        $sq->orWhere('username', 'like', "%{$query}%");
                    }
                    if (Schema::hasColumn('users', 'email')) {
                        $sq->orWhere('email', 'like', "%{$query}%");
                    }
                });

                // Search by barang name in items relation (barang has 'nama_barang')
                $q->orWhereHas('items.barang', function ($bq) use ($query) {
                    $bq->where('nama_barang', 'like', "%{$query}%");
                });
            });
        }

        $orders = $orders->paginate($perPage)->appends($request->except('page'));

        return view('admin.delivery-orders.index', compact('orders'));
    }

    // Approve order
    protected function processApproval($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'approved_warehouse';
        $order->save();
    }

    // Approve order
    public function approve($id)
    {
        $this->processApproval($id);
        return redirect()->route('delivery-orders.index')->with(['title' => 'Berhasil', 'text' => 'Order berhasil diapprove.']);
    }

    // Reject order
    protected function processRejection($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'rejected_warehouse';
        $order->save();
    }

    // Reject order
    public function reject($id)
    {
        $this->processRejection($id);
        return redirect()->route('delivery-orders.index')->with(['title' => 'Berhasil', 'text' => 'Order berhasil direject.']);
    }

    public function pdf($id)
    {
        $orders = Order::with('items.barang')->findOrFail($id);
        return view('admin.pdf.delivery-order-pdf', compact('orders'));
    }

    public function getItems($id)
    {
        $order = Order::with('items.barang')->findOrFail($id);
        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_barang' => $item->barang->kode_barang ?? ($item->kode_barang ?? '-'),
                'nama_barang' => $item->barang->nama_barang ?? ($item->nama_barang ?? '-'),
                'qty_pesanan' => $item->quantity,
                'stok_gudang' => ($item->barang && $item->barang->status_barang === 'masuk') ? $item->barang->stok : 0,
                'satuan' => $item->barang->satuan ?? ($item->satuan ?? '-'),
            ];
        });

        return response()->json($items);
    }
}
