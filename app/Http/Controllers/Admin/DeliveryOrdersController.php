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
        $orders = Order::with(['supervisor', 'items.barang'])
            ->where('status', 'sent_to_warehouse')
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
}
