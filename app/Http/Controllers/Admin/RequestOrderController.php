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
use Illuminate\Support\Facades\Storage;

class RequestOrderController extends Controller
{
    /**
     * List semua Request Order milik Sales
     */
    public function index()
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('request_orders', 'expired_at')) {
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
        }

        $query = RequestOrder::with('items.barang', 'sales')
            ->where('sales_id', Auth::id());

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('nomor_penawaran', 'like', "%{$search}%")
                    ->orWhere('sales_order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('catatan_customer', 'like', "%{$search}%")
                    ->orWhere('kategori_barang', 'like', "%{$search}%")
                    ->orWhere('grand_total', 'like', "%{$search}%");
            });
        }

        $requestOrders = $query->latest()
            ->paginate(request('perPage', 20))
            ->withQueryString();

        return view('admin.sales.request-order.index', compact('requestOrders'));
    }

    /**
     * Form untuk membuat Request Order baru
     */
    public function create()
    {
        $goods = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        // Get unique categories from goods
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

        return view('admin.sales.request-order.create', compact('goods', 'customers', 'categories', 'salesUsers'))->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil dibuat!']);
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
            'no_po' => 'nullable|string|max:255',
            'sales_order_number' => 'nullable|string|max:255',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:goods,id',
            'kategori_barang' => 'required|array|min:1',
            'kategori_barang.*' => 'required|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'diskon_percent' => 'nullable|array',
            'diskon_percent.*' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
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
            // calculate harga satuan based on baseHarga * 1.3
            $computedHargaSatuan = round($baseHarga * 1.3, 2);
            // If harga provided explicitly, we keep it but prefer computedHargaSatuan to be consistent
            $hargaSatuan = isset($validated['harga'][$i]) && $validated['harga'][$i] !== '' ? (float) $validated['harga'][$i] : $computedHargaSatuan;
            $subtotal = round($qty * $hargaSatuan * (1 - ($diskon / 100)), 2);

            $items[] = [
                'original_index' => $i,
                'barang_id' => $barangId,
                'kategori_barang' => $validated['kategori_barang'][$i] ?? null,
                'quantity' => $qty,
                'harga' => $hargaSatuan,
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

            // Calculate totals for the main request order
            $headerSubtotal = array_reduce($items, function($carry, $item) {
                return $carry + $item['subtotal'];
            }, 0);
            
            $headerTax = round($headerSubtotal * (($validated['tax_rate'] ?? 0) / 100), 2);
            $headerGrandTotal = round($headerSubtotal + $headerTax, 2);

            $requestOrder = RequestOrder::create([
                'request_number' => 'REQ-' . strtoupper(Str::random(8)),
                'nomor_penawaran' => $nomorPenawaran,
                'sales_order_number' => $validated['sales_order_number'] ?? null,
                'no_po' => $validated['no_po'] ?? null,
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
                'subtotal' => $headerSubtotal,
                'tax' => $headerTax,
                'grand_total' => $headerGrandTotal,
                // Initial status is 'open' so Sales can edit right after creating
                'status' => 'open',
            ]);

            foreach ($items as $item) {
                $origIdx = $item['original_index'];
                // handle per-item images
                $itemImagePaths = [];
                if ($request->hasFile("item_images.{$origIdx}")) {
                    foreach ($request->file("item_images.{$origIdx}") as $f) {
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
                    'images' => !empty($itemImagePaths) ? $itemImagePaths : null,
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

                // If the request order is for the current user, redirect to show; otherwise, redirect to index
                if ($requestOrder->sales_id == Auth::id()) {
                    return redirect()->route('sales.request-order.show', $requestOrder->id)
                        ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat.");
                } else {
                    return redirect()->route('sales.request-order.index')
                        ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat untuk sales lain.");
                }
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat Request Order: ' . $e->getMessage())->withInput()->with(['title' => 'Gagal', 'text' => 'Gagal membuat Request Order!']);
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

        // Allow overriding the PDF opening note via a query param (editable modal)
        $pdfNote = request()->query('pdf_note', $requestOrder->catatan_customer ?? null);

        return view('admin.pdf.request-order-pdf', compact('requestOrder', 'pdfNote'));
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

        $goods = Barang::where('tipe_request', 'primary')
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

        return view('admin.sales.request-order.edit', compact('requestOrder', 'goods', 'customers', 'categories', 'salesUsers'))->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diupdate!']);
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
            'no_po' => 'nullable|string|max:255',
            'sales_order_number' => 'nullable|string|max:255',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|integer|exists:goods,id',
            'kategori_barang' => 'required|array|min:1',
            'kategori_barang.*' => 'required|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
            'diskon_percent' => 'nullable|array',
            'diskon_percent.*' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
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
            $computedHarga = round($baseHarga * 1.3, 2);
            $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== '' ? (float) $validated['harga'][$i] : $computedHarga;
            $computedHarga = round($baseHarga * 1.3, 2);
            $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== '' ? (float) $validated['harga'][$i] : $computedHarga;
            $subtotal = round($qty * $harga * (1 - ($diskon / 100)), 2);

            $items[] = [
                'original_index' => $i,
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

            // Calculate totals for the main request order
            $headerSubtotal = array_reduce($items, function($carry, $item) {
                return $carry + $item['subtotal'];
            }, 0);
            
            $headerTax = round($headerSubtotal * (($validated['tax_rate'] ?? 0) / 100), 2);
            $headerGrandTotal = round($headerSubtotal + $headerTax, 2);

            $requestOrder->update([
                'sales_id' => $validated['pic_id'],
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subject' => $validated['subject'],
                'no_po' => $validated['no_po'] ?? null,
                'sales_order_number' => $validated['sales_order_number'] ?? null,
                'kategori_barang' => isset($validated['kategori_barang'][0]) ? $validated['kategori_barang'][0] : null,
                'tanggal_kebutuhan' => $validated['tanggal_kebutuhan'] ?? null,
                'catatan_customer' => $validated['catatan_customer'] ?? null,
                'supporting_images' => !empty($supportingImages) ? $supportingImages : null,
                'subtotal' => $headerSubtotal,
                'tax' => $headerTax,
                'grand_total' => $headerGrandTotal,
            ]);

            // Hapus item lama
            $requestOrder->items()->delete();

            // Tambah item baru
            foreach ($items as $item) {
                $origIdx = $item['original_index'];
                $itemImagePaths = [];
                if ($request->hasFile("item_images.{$origIdx}")) {
                    foreach ($request->file("item_images.{$origIdx}") as $f) {
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
                ->with('success', 'Request Order berhasil diubah.')->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diubah!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Request Order: ' . $e->getMessage())->withInput()->with(['title' => 'Gagal', 'text' => 'Gagal mengubah Request Order!']);
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

        return back()->with('success', 'Request Order telah disetujui oleh Supervisor.')->with(['title' => 'Berhasil', 'text' => 'Request Order telah disetujui oleh Supervisor!']);
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

        return back()->with('success', 'Request Order ditolak.')->with(['title' => 'Berhasil', 'text' => 'Request Order ditolak!']);
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

        return back()->with('success', 'Status Request Order diperbarui.')->with(['title' => 'Berhasil', 'text' => 'Status Request Order diperbarui!']);
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

        try {
            $this->processSentToWarehouse($requestOrder);
            return redirect()->route('sales.request-order.index')
                ->with('success', "Request Order berhasil dikirim ke Warehouse.")->with(['title' => 'Berhasil', 'text' => 'Order berhasil dikirim ke Warehouse!']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal mengirim ke Warehouse: ' . $e->getMessage())->with(['title' => 'Gagal', 'text' => 'Gagal mengirim ke Warehouse!']);
        }
    }

    /**
     * Hapus Request Order
     */
    public function destroy(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($requestOrder) {
                $requestOrder->items()->delete();
                $requestOrder->delete();
            });
            return back()->with('success', 'Request Order berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Request Order: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Delete Request Orders
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        DB::beginTransaction();
        try {
            $requestOrders = RequestOrder::whereIn('id', $ids)
                ->where('sales_id', Auth::id())
                ->get();

            foreach ($requestOrders as $ro) {
                $ro->items()->delete();
                $ro->delete();
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Bulk Send to Warehouse
     */
    public function bulkSendToWarehouse(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        $successCount = 0;
        foreach ($ids as $id) {
            try {
                $ro = RequestOrder::where('id', $id)
                    ->where('sales_id', Auth::id())
                    ->first();

                if ($ro && in_array($ro->status, ['open', 'approved']) && !$ro->order) {
                    $this->processSentToWarehouse($ro);
                    $successCount++;
                }
            } catch (\Throwable $e) {
                // Skip failed ones in bulk
            }
        }

        return response()->json([
            'success' => $successCount > 0,
            'count' => $successCount
        ]);
    }

    /**
     * Upload image SO for request order
     */
    public function uploadImageSO(Request $request, RequestOrder $requestOrder)
    {
        if ($request->hasFile('image_so')) {
            $path = $request->file('image_so')->store('request-order-so-images', 'public');
            $requestOrder->image_so = $path;
            $requestOrder->save();
            return response()->json(['status' => 'success', 'image_url' => Storage::url($path)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    protected function processSentToWarehouse(RequestOrder $ro)
    {
        DB::transaction(function () use ($ro) {
            $order = Order::create([
                'order_number' => 'DO-' . strtoupper(Str::random(8)),
                'sales_id' => Auth::id(),
                'supervisor_id' => $ro->approved_by,
                'request_order_id' => $ro->id,
                'status' => 'sent_to_warehouse',
                'customer_name' => $ro->customer_name,
                'customer_id' => $ro->customer_id,
                'tanggal_kebutuhan' => $ro->tanggal_kebutuhan,
                'catatan_customer' => $ro->catatan_customer,
            ]);

            foreach ($ro->items as $reqItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $reqItem->barang_id,
                    'quantity' => $reqItem->quantity,
                    'harga' => $reqItem->harga,
                    'subtotal' => $reqItem->subtotal,
                ]);
            }

            $ro->update(['status' => 'sent_to_warehouse']);
        });
    }
}
