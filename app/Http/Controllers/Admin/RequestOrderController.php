<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\RequestOrder;
use App\Models\RequestOrderItem;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RequestOrderController extends Controller
{
    /**
     * List semua Request Order milik Sales
     */
    public function index()
    {
        $requestOrders = RequestOrder::with('items.barang', 'sales')
            ->where('sales_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('admin.sales.request-order.index', compact('requestOrders'));
    }

    /**
     * Form untuk membuat Request Order baru
     */
    public function create()
    {
        $barangs = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        // Get unique categories from barangs
        $categories = Barang::distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori')
            ->sort()
            ->values();

        return view('admin.sales.request-order.create', compact('barangs', 'customers', 'categories'));
    }

    /**
     * Simpan Request Order baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'kategori_barang' => 'required|string|max:100',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:barangs,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'supporting_images' => 'nullable|array',
            'supporting_images.*' => 'nullable|image|max:5120',
            'item_images' => 'nullable|array',
            'item_images.*' => 'nullable|array',
            'item_images.*.*' => 'nullable|image|max:5120',
        ]);

        $items = [];
        foreach ($validated['barang_id'] as $i => $barangId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) continue;

            $harga = isset($validated['harga'][$i]) ? (float) $validated['harga'][$i] : 0;
            $subtotal = $qty * $harga;

            $items[] = [
                'barang_id' => $barangId,
                'quantity' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($items)) {
            return back()->withErrors('Tidak ada item valid.')->withInput();
        }

    DB::beginTransaction();
        try {
            // Generate nomor penawaran
            $nomorPenawaran = RequestOrder::generateNomorPenawaran();
            
            // Calculate tanggal berlaku (14 hari dari sekarang)
            $tanggalBerlaku = now()->addDays(14);
            
            // Handle supporting images
            $supportingImages = [];
            if ($request->hasFile('supporting_images')) {
                foreach ($request->file('supporting_images') as $file) {
                    $path = $file->store('request-order-images', 'public');
                    $supportingImages[] = $path;
                }
            }

            $requestOrder = RequestOrder::create([
                'request_number' => 'REQ-' . strtoupper(Str::random(8)),
                'nomor_penawaran' => $nomorPenawaran,
                'sales_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'kategori_barang' => $validated['kategori_barang'],
                'tanggal_kebutuhan' => $validated['tanggal_kebutuhan'] ?? null,
                'tanggal_berlaku' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'catatan_customer' => $validated['catatan_customer'] ?? null,
                'supporting_images' => !empty($supportingImages) ? $supportingImages : null,
                // Determine status based on max diskon of selected items
                'status' => 'pending',
            ]);

            foreach ($items as $i => $item) {
                // handle per-item images
                $itemImagePaths = [];
                if ($request->hasFile('item_images') && isset($request->file('item_images')[$i])) {
                    foreach ($request->file('item_images')[$i] as $f) {
                        if ($f) {
                            $itemImagePaths[] = $f->store('request-order-item-images', 'public');
                        }
                    }
                }

                RequestOrderItem::create([
                    'request_order_id' => $requestOrder->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'item_images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                ]);
            }

            // Check discount rules: if any barang has diskon_percent >= 30 then require supervisor approval
            $barangIds = array_column($items, 'barang_id');
            $diskonMap = Barang::whereIn('id', $barangIds)->pluck('diskon_percent', 'id')->toArray();
            $maxDiskon = 0;
            foreach ($barangIds as $id) {
                $d = isset($diskonMap[$id]) ? (int)$diskonMap[$id] : 0;
                if ($d > $maxDiskon) $maxDiskon = $d;
            }

            if ($maxDiskon >= 30) {
                // Mark as pending approval by supervisor
                $requestOrder->update(['status' => 'pending_approval']);

                DB::commit();

                return redirect()->route('sales.request-order.index')
                    ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat dan menunggu persetujuan.");
            } else {
                // Auto-approve (sales can send directly)
                $requestOrder->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);

                // Create Sales Order immediately from approved Request Order
                $salesOrder = SalesOrder::create([
                    'sales_order_number' => 'SO-' . strtoupper(Str::random(8)),
                    'request_order_id' => $requestOrder->id,
                    'sales_id' => Auth::id(),
                    'customer_name' => $requestOrder->customer_name,
                    'customer_id' => $requestOrder->customer_id,
                    'tanggal_kebutuhan' => $requestOrder->tanggal_kebutuhan,
                    'catatan_customer' => $requestOrder->catatan_customer,
                    'status' => 'pending',
                ]);

                foreach ($requestOrder->items as $reqItem) {
                    SalesOrderItem::create([
                        'sales_order_id' => $salesOrder->id,
                        'request_order_item_id' => $reqItem->id,
                        'barang_id' => $reqItem->barang_id,
                        'quantity' => $reqItem->quantity,
                        'harga' => $reqItem->harga,
                        'subtotal' => $reqItem->subtotal,
                        'status_item' => 'pending',
                    ]);
                }

                // Update status Request Order to converted
                $requestOrder->update(['status' => 'converted']);

                DB::commit();

                return redirect()->route('sales.sales-order.show', $salesOrder->id)
                    ->with('success', "Sales Order {$salesOrder->sales_order_number} berhasil dibuat dari Request Order.");
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat Request Order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail Request Order
     */
    public function show(RequestOrder $requestOrder)
    {
        // Pastikan hanya pemilik atau supervisor/warehouse yang bisa lihat
        if ($requestOrder->sales_id !== Auth::id() && !in_array(Auth::user()->role, ['Supervisor', 'Warehouse', 'Admin'])) {
            abort(403);
        }

        $requestOrder->load('items.barang', 'sales', 'approvedBy');

        return view('admin.sales.request-order.show', compact('requestOrder'));
    }

    /**
     * Form edit Request Order (hanya jika masih pending)
     */
    public function edit(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->status !== 'pending') {
            return back()->withErrors('Hanya Request Order yang pending dapat diubah.');
        }

        $barangs = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        $categories = Barang::distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori')->sort()->values();

        return view('admin.sales.request-order.edit', compact('requestOrder', 'barangs', 'customers', 'categories'));
    }

    /**
     * Update Request Order
     */
    public function update(Request $request, RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->status !== 'pending') {
            return back()->withErrors('Hanya Request Order yang pending dapat diubah.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'kategori_barang' => 'required|string|max:100',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:barangs,id',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'supporting_images' => 'nullable|array',
            'supporting_images.*' => 'nullable|image|max:5120',
            'item_images' => 'nullable|array',
            'item_images.*' => 'nullable|array',
            'item_images.*.*' => 'nullable|image|max:5120',
        ]);

        $items = [];
        foreach ($validated['barang_id'] as $i => $barangId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) continue;

            $harga = isset($validated['harga'][$i]) ? (float) $validated['harga'][$i] : 0;
            $subtotal = $qty * $harga;

            $items[] = [
                'barang_id' => $barangId,
                'quantity' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($items)) {
            return back()->withErrors('Tidak ada item valid.')->withInput();
        }

        DB::beginTransaction();
        try {
            // Handle supporting images
            $supportingImages = $requestOrder->supporting_images ?? [];
            if ($request->hasFile('supporting_images')) {
                foreach ($request->file('supporting_images') as $file) {
                    $path = $file->store('request-order-images', 'public');
                    $supportingImages[] = $path;
                }
            }

            $requestOrder->update([
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'kategori_barang' => $validated['kategori_barang'],
                'tanggal_kebutuhan' => $validated['tanggal_kebutuhan'] ?? null,
                'catatan_customer' => $validated['catatan_customer'] ?? null,
                'supporting_images' => !empty($supportingImages) ? $supportingImages : null,
            ]);

            // Hapus item lama
            $requestOrder->items()->delete();

            // Tambah item baru
            foreach ($items as $i => $item) {
                $itemImagePaths = [];
                if ($request->hasFile('item_images') && isset($request->file('item_images')[$i])) {
                    foreach ($request->file('item_images')[$i] as $f) {
                        if ($f) {
                            $itemImagePaths[] = $f->store('request-order-item-images', 'public');
                        }
                    }
                }

                RequestOrderItem::create([
                    'request_order_id' => $requestOrder->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'item_images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                ]);
            }

            // Re-evaluate discount rule after update
            $barangIds = array_column($items, 'barang_id');
            $diskonMap = Barang::whereIn('id', $barangIds)->pluck('diskon_percent', 'id')->toArray();
            $maxDiskon = 0;
            foreach ($barangIds as $id) {
                $d = isset($diskonMap[$id]) ? (int)$diskonMap[$id] : 0;
                if ($d > $maxDiskon) $maxDiskon = $d;
            }

            if ($maxDiskon >= 30) {
                $requestOrder->update(['status' => 'pending_approval']);
            } else {
                $requestOrder->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('sales.request-order.show', $requestOrder->id)
                ->with('success', 'Request Order berhasil diubah.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Request Order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Konversi Request Order ke Sales Order
     */
    public function convertToSalesOrder(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->status !== 'approved') {
            return back()->withErrors('Hanya Request Order yang approved dapat dikonversi ke Sales Order.');
        }

        if ($requestOrder->salesOrder) {
            return back()->withErrors('Request Order ini sudah dikonversi ke Sales Order.');
        }

        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::create([
                'sales_order_number' => 'SO-' . strtoupper(Str::random(8)),
                'request_order_id' => $requestOrder->id,
                'sales_id' => Auth::id(),
                'customer_name' => $requestOrder->customer_name,
                'customer_id' => $requestOrder->customer_id,
                'tanggal_kebutuhan' => $requestOrder->tanggal_kebutuhan,
                'catatan_customer' => $requestOrder->catatan_customer,
                'status' => 'pending',
            ]);

            
            foreach ($requestOrder->items as $reqItem) {
                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'request_order_item_id' => $reqItem->id,
                    'barang_id' => $reqItem->barang_id,
                    'quantity' => $reqItem->quantity,
                    'harga' => $reqItem->harga,
                    'subtotal' => $reqItem->subtotal,
                    'status_item' => 'pending',
                ]);
            }

            // Update status Request Order
            $requestOrder->update(['status' => 'converted']);

            DB::commit();

            return redirect()->route('sales.sales-order.show', $salesOrder->id)
                ->with('success', "Sales Order {$salesOrder->sales_order_number} berhasil dibuat dari Request Order.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat Sales Order: ' . $e->getMessage());
        }
    }

    /**
     * Supervisor approves a Request Order that required approval
     */
    public function supervisorApprove(RequestOrder $requestOrder)
    {
        // Only supervisors should call this (route middleware enforces it)
        if ($requestOrder->status !== 'pending_approval') {
            return back()->withErrors('Request Order tidak memerlukan persetujuan atau sudah diproses.');
        }

        $requestOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Request Order telah disetujui oleh Supervisor.');
    }

    /**
     * Supervisor rejects a Request Order that required approval
     */
    public function supervisorReject(Request $request, RequestOrder $requestOrder)
    {
        if ($requestOrder->status !== 'pending_approval') {
            return back()->withErrors('Request Order tidak dapat ditolak pada status ini.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $requestOrder->update([
            'status' => 'rejected',
            'reason' => $request->input('reason'),
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Request Order ditolak.');
    }
}
