<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\RequestOrder;
use App\Models\RequestOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RequestOrderController extends Controller
{
    /**
     * List semua Request Order milik Sales
     */
    public function index()
    {
        // First, extend any pending request orders that were created with an older 7-day expiry
        // to be 14 days from now if they are still unexpired and were likely set with 7 days.
        \Illuminate\Support\Facades\DB::table('request_orders')
            ->whereIn('status', ['pending', 'open'])
            ->whereNotNull('created_at')
            ->whereNotNull('expired_at')
            ->where('expired_at', '>', now())
            ->whereRaw('TIMESTAMPDIFF(DAY, created_at, expired_at) <= 8')
            ->where('sales_id', Auth::id())
            ->update(['expired_at' => \Illuminate\Support\Facades\DB::raw("DATE_ADD(NOW(), INTERVAL 14 DAY)")]);

        // Ensure any pending RequestOrders past their expiry are marked expired
        RequestOrder::whereIn('status', ['pending', 'open'])
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->where('sales_id', Auth::id())
            ->update(['status' => 'expired']);

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

        // Get sales users
        $salesUsers = \App\Models\User::where('role', 'Sales')
            ->orderBy('name')
            ->get();

        return view('admin.sales.request-order.create', compact('barangs', 'customers', 'categories', 'salesUsers'));
    }

    /**
     * Simpan Request Order baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'pic_id' => 'required|integer|exists:users,id',
            'subject' => 'required|string|max:255',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:barangs,id',
            'kategori_barang' => 'required|array|min:1',
            'kategori_barang.*' => 'required|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'diskon_percent' => 'nullable|array',
            'diskon_percent.*' => 'nullable|numeric|min:0|max:100',
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

            // If harga wasn't provided from the form, fallback to Barang.harga * 1.3
            $baseHarga = optional(Barang::find($barangId))->harga ?? 0;
            // compute diskon percentage from input, fallback to 0
            $diskon = isset($validated['diskon_percent'][$i]) && $validated['diskon_percent'][$i] !== '' ? (float) $validated['diskon_percent'][$i] : 0;
            // calculate harga based on baseHarga * 1.3 (jual) then apply discount
            $computedHarga = round($baseHarga * 1.3 * (1 - ($diskon / 100)), 2);
            // If harga provided explicitly, we keep it but prefer computedHarga to be consistent
            $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== '' ? (float) $validated['harga'][$i] : $computedHarga;
            $subtotal = $qty * $harga;

            $items[] = [
                'barang_id' => $barangId,
                'kategori_barang' => $validated['kategori_barang'][$i] ?? null,
                'quantity' => $qty,
                'harga' => $harga,
                'diskon_percent' => $diskon,
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
                'sales_order_number' => 'SO-' . strtoupper(Str::random(8)),
                'sales_id' => $validated['pic_id'],
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subject' => $validated['subject'],
                'kategori_barang' => isset($validated['kategori_barang'][0]) ? $validated['kategori_barang'][0] : null,
                'tanggal_kebutuhan' => $validated['tanggal_kebutuhan'] ?? null,
                'tanggal_berlaku' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'catatan_customer' => $validated['catatan_customer'] ?? null,
                'supporting_images' => !empty($supportingImages) ? $supportingImages : null,
                // Initial status is 'open' so Sales can edit right after creating
                'status' => 'open',
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

                $itemData = [
                    'request_order_id' => $requestOrder->id,
                    'barang_id' => $item['barang_id'],
                    'kategori_barang' => $item['kategori_barang'] ?? null,
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'item_images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                ];

                if (Schema::hasColumn('request_order_items', 'diskon_percent')) {
                    $itemData['diskon_percent'] = $item['diskon_percent'] ?? 0;
                }

                RequestOrderItem::create($itemData);
            }

            // Check discount rules: if any item has diskon_percent > 20 then require supervisor approval
            $maxDiskon = 0;
            foreach ($items as $it) {
                $d = isset($it['diskon_percent']) ? (float)$it['diskon_percent'] : 0;
                if ($d > $maxDiskon) $maxDiskon = $d;
            }

            if ($maxDiskon > 20) {
                // Mark as pending approval by supervisor
                $requestOrder->update(['status' => 'pending_approval']);

                DB::commit();

                return redirect()->route('sales.request-order.index')
                    ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat dan menunggu persetujuan.");
            } else {
                // Keep newly created Request Order in 'open' state so Sales can review/edit before any conversion
                $requestOrder->update([
                    'status' => 'open',
                ]);

                DB::commit();

                return redirect()->route('sales.request-order.show', $requestOrder->id)
                    ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat.");
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
        // Pastikan hanya pemilik atau supervisor/warehouse yang bisa lihat (case-insensitive role check)
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Warehouse', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        // If this request order appears to have been created with a 7-day expiry
        // and is still unexpired, extend it to 14 days from now (as requested)
        if ($requestOrder->expired_at && $requestOrder->created_at) {
            try {
                $diffDays = \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->diffInDays($requestOrder->created_at);
                if ($diffDays <= 8 && \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->greaterThan(now())) {
                    $requestOrder->update(['expired_at' => now()->addDays(14)]);
                }
            } catch (\Throwable $e) {
                // ignore parsing errors and continue
            }
        }

        // Ensure this request order's expiry status is up-to-date
        $requestOrder->checkAndUpdateExpiry();
        $requestOrder->refresh();
        $requestOrder->load('items.barang', 'sales', 'approvedBy');

        return view('admin.sales.request-order.show', compact('requestOrder'));
    }

    /**
     * View PDF for Request Order
     */
    public function pdf(RequestOrder $requestOrder)
    {
        // Authorization (case-insensitive role check):
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $adminAllowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if (in_array($userRole, $adminAllowed)) {
            // allowed
        } elseif ($requestOrder->sales_id === Auth::id()) {
            // owner
            if (in_array($requestOrder->status, ['pending_approval', 'rejected'])) {
                abort(403);
            }
        } else {
            abort(403);
        }

        // Ensure expiry status is up-to-date before generating PDF
        $requestOrder->checkAndUpdateExpiry();
        $requestOrder->refresh();
        $requestOrder->load('items.barang', 'sales');
        return view('admin.pdf.request-order-pdf', compact('requestOrder'));
    }

    /**
     * Form edit Request Order (hanya jika masih pending)
     */
    public function edit(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        // Allow editing when status is 'open' or 'pending'
        if (!in_array($requestOrder->status, ['open', 'pending'])) {
            return back()->withErrors('Hanya Request Order yang open atau pending dapat diubah.');
        }

        // If this request order appears to have been created with a 7-day expiry
        // and is still unexpired, extend it to 14 days from now (as requested)
        if ($requestOrder->expired_at && $requestOrder->created_at) {
            try {
                $diffDays = \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->diffInDays($requestOrder->created_at);
                if ($diffDays <= 8 && \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->greaterThan(now())) {
                    $requestOrder->update(['expired_at' => now()->addDays(14)]);
                }
            } catch (\Throwable $e) {
                // ignore parsing errors and continue
            }
        }

        $barangs = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        $categories = Barang::distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori')->sort()->values();

        // Get sales users
        $salesUsers = \App\Models\User::where('role', 'Sales')
            ->orderBy('name')
            ->get();

        return view('admin.sales.request-order.edit', compact('requestOrder', 'barangs', 'customers', 'categories', 'salesUsers'));
    }

    /**
     * Update Request Order
     */
    public function update(Request $request, RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        // Allow updating when status is 'open' or 'pending'
        if (!in_array($requestOrder->status, ['open', 'pending'])) {
            return back()->withErrors('Hanya Request Order yang open atau pending dapat diubah.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'pic_id' => 'required|integer|exists:users,id',
            'subject' => 'required|string|max:255',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:barangs,id',
            'kategori_barang' => 'required|array|min:1',
            'kategori_barang.*' => 'required|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'diskon_percent' => 'nullable|array',
            'diskon_percent.*' => 'nullable|numeric|min:0|max:100',
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

            // If harga wasn't provided from the form, fallback to Barang.harga * 1.3
            $baseHarga = optional(Barang::find($barangId))->harga ?? 0;
            $diskon = isset($validated['diskon_percent'][$i]) && $validated['diskon_percent'][$i] !== '' ? (float) $validated['diskon_percent'][$i] : 0;
            $computedHarga = round($baseHarga * 1.3 * (1 - ($diskon / 100)), 2);
            $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== '' ? (float) $validated['harga'][$i] : $computedHarga;
            $subtotal = $qty * $harga;

            $items[] = [
                'barang_id' => $barangId,
                'kategori_barang' => $validated['kategori_barang'][$i] ?? null,
                'quantity' => $qty,
                'harga' => $harga,
                'diskon_percent' => $diskon,
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
                'sales_id' => $validated['pic_id'],
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subject' => $validated['subject'],
                'kategori_barang' => isset($validated['kategori_barang'][0]) ? $validated['kategori_barang'][0] : null,
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

                $itemData = [
                    'request_order_id' => $requestOrder->id,
                    'barang_id' => $item['barang_id'],
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'item_images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                ];

                if (Schema::hasColumn('request_order_items', 'diskon_percent')) {
                    $itemData['diskon_percent'] = $item['diskon_percent'] ?? 0;
                }

                RequestOrderItem::create($itemData);
            }

            // Re-evaluate discount rule after update based on provided diskon_percent
            $maxDiskon = 0;
            foreach ($items as $it) {
                $d = isset($it['diskon_percent']) ? (float)$it['diskon_percent'] : 0;
                if ($d > $maxDiskon) $maxDiskon = $d;
            }

            if ($maxDiskon > 20) {
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

    /**
     * Update status for a request order (Sales).
     */
    public function updateStatus(Request $request, RequestOrder $requestOrder)
    {
        // Only allow owner Sales to change status here
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string|in:open,pending,expired,complete',
        ]);

        // Update status
        $requestOrder->update(['status' => $validated['status']]);

        return back()->with('success', 'Status Request Order diperbarui.');
    }

    /**
     * Sent Request Order to Warehouse (create Order with status sent_to_warehouse)
     */
    public function sentToWarehouse(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($requestOrder->status, ['open', 'approved'])) {
            return back()->withErrors('Hanya Request Order yang open atau approved dapat dikirim ke Warehouse.');
        }

        if ($requestOrder->order) {
            return back()->withErrors('Request Order ini sudah dikirim ke Warehouse.');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => 'DO-' . strtoupper(Str::random(8)),
                'sales_id' => Auth::id(),
                'supervisor_id' => $requestOrder->approved_by, // assuming approved_by is supervisor
                'request_order_id' => $requestOrder->id,
                'status' => 'sent_to_warehouse',
                'customer_name' => $requestOrder->customer_name,
                'customer_id' => $requestOrder->customer_id,
                'tanggal_kebutuhan' => $requestOrder->tanggal_kebutuhan,
                'catatan_customer' => $requestOrder->catatan_customer,
            ]);

            foreach ($requestOrder->items as $reqItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $reqItem->barang_id,
                    'quantity' => $reqItem->quantity,
                    'harga' => $reqItem->harga,
                    'subtotal' => $reqItem->subtotal,
                ]);
            }

            // Update Request Order status to sent_to_warehouse or keep approved
            $requestOrder->update(['status' => 'sent_to_warehouse']);

            DB::commit();

            return redirect()->route('sales.request-order.index')
                ->with('success', "Order {$order->order_number} berhasil dikirim ke Warehouse.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengirim ke Warehouse: ' . $e->getMessage());
        }
    }
}
