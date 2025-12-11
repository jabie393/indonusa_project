<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * List semua Sales Order milik Sales
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $salesOrders = SalesOrder::with('items.barang', 'sales', 'requestOrder')
            ->where('sales_id', Auth::id());

        if ($query) {
            $salesOrders = $salesOrders->where(function ($q) use ($query) {
                $q->where('sales_order_number', 'like', "%{$query}%")
                    ->orWhere('customer_name', 'like', "%{$query}%")
                    ->orWhereHas('requestOrder', fn($subQ) => $subQ->where('request_number', 'like', "%{$query}%"));
            });
        }

        $salesOrders = $salesOrders->latest()->paginate(20)->appends($request->except('page'));

        return view('admin.sales.sales-order.index', compact('salesOrders'));
    }

    /**
     * Lihat detail Sales Order
     */
    public function show(SalesOrder $salesOrder)
    {
        // Pastikan hanya pemilik atau supervisor/warehouse yang bisa lihat
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Warehouse', 'Admin']);
        if ($salesOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        $salesOrder->load('items.barang', 'sales', 'requestOrder', 'supervisor', 'warehouse');

        return view('admin.sales.sales-order.show', compact('salesOrder'));
    }

    /**
     * Update status Sales Order
     */
    public function updateStatus(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_process,shipped,completed,cancelled',
            'reason' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $salesOrder->update([
                'status' => $validated['status'],
                'reason' => $validated['reason'] ?? null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => 'Status Sales Order berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Update delivered quantity untuk item
     */
    public function updateDeliveredQty(Request $request, SalesOrderItem $item)
    {
        $salesOrder = $item->salesOrder;

        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'delivered_quantity' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $deliveredQty = $validated['delivered_quantity'];

            if ($deliveredQty > $item->quantity) {
                throw new \Exception('Delivered quantity tidak boleh melebihi quantity yang diminta.');
            }

            $item->update([
                'delivered_quantity' => $deliveredQty,
                'status_item' => $deliveredQty == 0 ? 'pending' : ($deliveredQty == $item->quantity ? 'completed' : 'partial'),
            ]);

            // Cek apakah semua items completed
            $allCompleted = $salesOrder->items->every(fn($i) => $i->status_item === 'completed');
            if ($allCompleted && $salesOrder->status !== 'completed') {
                $salesOrder->update(['status' => 'completed']);
            }

            DB::commit();

            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => 'Delivered quantity berhasil diupdate.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Cancel Sales Order
     */
    public function cancel(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($salesOrder->status === 'completed' || $salesOrder->status === 'cancelled') {
            return back()->withErrors('Sales Order yang sudah completed atau cancelled tidak dapat dibatalkan.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        DB::beginTransaction();
        try {
            $salesOrder->update([
                'status' => 'cancelled',
                'reason' => $validated['reason'],
            ]);

            DB::commit();

            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => 'Sales Order berhasil dibatalkan.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membatalkan Sales Order: ' . $e->getMessage());
        }
    }
}
