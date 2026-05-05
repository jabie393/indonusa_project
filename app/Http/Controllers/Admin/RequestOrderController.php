<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RequestOrder;
use App\Models\RequestOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RequestOrderController extends Controller
{
    public function supervisorApprove(Request $request, $id)
    {
        $order = Order::where('request_order_id', $id)->first();
        if (! $order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }
        $order->update([
            'status' => 'approved_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil di-approve oleh supervisor.']);
    }

    public function supervisorReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $order = Order::where('request_order_id', $id)->first();
        if (! $order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }

        $order->update([
            'status' => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
            'reason' => $request->reason,
        ]);

        $requestOrder = RequestOrder::find($id);
        if ($requestOrder) {
            $requestOrder->update(['reason' => $request->reason]);
        }

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil ditolak.']);
    }

    public function index()
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('request_orders', 'expired_at')) {
            \Illuminate\Support\Facades\DB::table('request_orders')
                ->whereNotNull('created_at')
                ->whereNotNull('expired_at')
                ->where('expired_at', '>', now())
                ->whereRaw('TIMESTAMPDIFF(DAY, created_at, expired_at) <= 8')
                ->where('sales_id', Auth::id())
                ->update(['expired_at' => \Illuminate\Support\Facades\DB::raw('DATE_ADD(NOW(), INTERVAL 14 DAY)')]);
        }

        $query = RequestOrder::with(['items.barang', 'sales', 'order.items.barang'])
            ->where('sales_id', Auth::id());

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('nomor_penawaran', 'like', "%{$search}%")
                    ->orWhere('sales_order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
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

    public function create()
    {
        $goods = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        $categories = Barang::distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->pluck('kategori')
            ->sort()
            ->values();

        $salesUsers = \App\Models\User::where('role', 'Sales')
            ->orderBy('name')
            ->get();

        return view('admin.sales.request-order.create', compact('goods', 'customers', 'categories', 'salesUsers'))
            ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil dibuat!']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'pic_id' => 'required|integer|exists:users,id',
            'subject' => 'required|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'tanggal_kebutuhan' => 'nullable|date',
            'catatan_customer' => 'nullable|string',
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'nullable',
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
            'nama_barang_custom' => 'nullable|array',
            'nama_barang_custom.*' => 'nullable|string|max:255',
        ]);

        $items = [];
        $maxDiskon = 0;
        foreach ($validated['barang_id'] as $i => $barangId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) {
                continue;
            }

            $baseHarga = optional(Barang::find($barangId))->harga ?? 0;
            $diskon = isset($validated['diskon_percent'][$i]) && $validated['diskon_percent'][$i] !== '' ? (float) $validated['diskon_percent'][$i] : 0;
            $computedHargaSatuan = round($baseHarga * 1.3, 2);
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
            if ($diskon > $maxDiskon) {
                $maxDiskon = $diskon;
            }
        }

        if (empty($items)) {
            return back()->withErrors('Tidak ada item valid.')->withInput();
        }

        DB::beginTransaction();
        try {
            $nomorPenawaran = RequestOrder::generateNomorPenawaran();
            $tanggalBerlaku = now()->addDays(14);

            $supportingImages = [];
            if ($request->hasFile('supporting_images')) {
                foreach ($request->file('supporting_images') as $file) {
                    $path = $file->store('request-order-images', 'public');
                    $supportingImages[] = $path;
                }
            }

            $headerSubtotal = array_reduce($items, fn ($carry, $item) => $carry + $item['subtotal'], 0);
            $headerTax = round($headerSubtotal * (($validated['tax_rate'] ?? 0) / 100), 2);
            $headerGrandTotal = round($headerSubtotal + $headerTax, 2);

            $salesOrderNumber = RequestOrder::generateSalesOrderNumber();
            $requestOrder = RequestOrder::create([
                'request_number' => 'REQ-'.strtoupper(Str::random(8)),
                'nomor_penawaran' => $nomorPenawaran,
                'sales_order_number' => $salesOrderNumber,
                'no_po' => $validated['no_po'] ?? null,
                'sales_id' => $validated['pic_id'],
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subject' => $validated['subject'],
                'kategori_barang' => $validated['kategori_barang'][0] ?? null,
                'tanggal_kebutuhan' => $validated['tanggal_kebutuhan'] ?? null,
                'tanggal_berlaku' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'catatan_customer' => $validated['catatan_customer'] ?? null,
                'supporting_images' => ! empty($supportingImages) ? $supportingImages : null,
                'subtotal' => $headerSubtotal,
                'tax' => $headerTax,
                'grand_total' => $headerGrandTotal,
            ]);

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
                    'kategori_barang' => $item['kategori_barang'] ?? null,
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'images' => ! empty($itemImagePaths) ? $itemImagePaths : null,
                ];

                if (Schema::hasColumn('request_order_items', 'diskon_percent')) {
                    $itemData['diskon_percent'] = $item['diskon_percent'] ?? 0;
                }

                RequestOrderItem::create($itemData);
            }

            $requestOrder->refresh();
            $requestOrder->load('items');
            $maxDiskon = $requestOrder->items->max('diskon_percent') ?? 0;

            foreach ($items as $item) {
                $barangId = $item['barang_id'] ?? null;
                $qty = (int) ($item['quantity'] ?? 0);
                if ($barangId && $qty > 0) {
                    \App\Models\Barang::where('id', $barangId)
                        ->where('stok', '>=', $qty)
                        ->decrement('stok', $qty);
                }
            }

            $orderStatus = $maxDiskon > 20 ? 'sent_to_supervisor' : 'open';

            $existingOrder = Order::where('request_order_id', $requestOrder->id)->first();
            if (! $existingOrder) {
                Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                    'sales_id' => $requestOrder->sales_id,
                    'customer_name' => $requestOrder->customer_name,
                    'customer_id' => $requestOrder->customer_id,
                    'request_order_id' => $requestOrder->id,
                    'status' => $orderStatus,
                    'tanggal_kebutuhan' => $requestOrder->tanggal_kebutuhan,
                    'catatan_customer' => $requestOrder->catatan_customer,
                ]);
            } else {
                $existingOrder->update(['status' => $orderStatus]);
            }

            DB::commit();

            if ($requestOrder->sales_id == Auth::id()) {
                return redirect()->route('sales.request-order.show', $requestOrder->id)
                    ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat.");
            } else {
                return redirect()->route('sales.request-order.index')
                    ->with('success', "Request Order {$requestOrder->request_number} berhasil dibuat untuk sales lain.");
            }

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors('Gagal membuat Request Order: '.$e->getMessage())
                ->withInput()
                ->with(['title' => 'Gagal', 'text' => 'Gagal membuat Request Order!']);
        }
    }

    public function show(RequestOrder $requestOrder)
    {
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Warehouse', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && ! in_array($userRole, $allowed)) {
            abort(403);
        }

        if ($requestOrder->expired_at && $requestOrder->created_at) {
            try {
                $diffDays = \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->diffInDays($requestOrder->created_at);
                if ($diffDays <= 8 && \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->greaterThan(now())) {
                    $requestOrder->update(['expired_at' => now()->addDays(14)]);
                }
            } catch (\Throwable $e) {
            }
        }

        $requestOrder->refresh();
        $requestOrder->load('items.barang', 'sales', 'approvedBy');

        return view('admin.sales.request-order.show', compact('requestOrder'));
    }

    public function pdf(RequestOrder $requestOrder)
    {
        $requestOrder->loadMissing('items', 'order');
        if (! $requestOrder->canDownloadPdf()) {
            $status = $requestOrder->order?->status;
            if ($requestOrder->hasDiscountOver20()) {
                if ($status === 'pending') {
                    return redirect()->back()->withErrors('PDF tidak dapat didownload, menunggu persetujuan supervisor.');
                }
                if ($status === 'rejected_supervisor') {
                    return redirect()->back()->withErrors('PDF tidak dapat didownload, ditolak oleh supervisor.');
                }
            }

            return redirect()->back()->withErrors('PDF tidak dapat didownload.');
        }

        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $adminAllowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if (in_array($userRole, $adminAllowed)) {
            // allowed
        } elseif ($requestOrder->sales_id === Auth::id()) {
            if (in_array($requestOrder->status, ['pending_approval', 'rejected'])) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $requestOrder->refresh();
        $requestOrder->load('items.barang', 'sales');

        $pdfNote = request()->query('pdf_note', $requestOrder->catatan_customer ?? null);

        $html = view('admin.pdf.request-order-pdf', compact('requestOrder', 'pdfNote'))->render();

        $footerLogoPath = public_path('images/footer_logo.png');
        $footerLogoBase64 = '';
        if (file_exists($footerLogoPath)) {
            $mime = mime_content_type($footerLogoPath);
            $data = base64_encode(file_get_contents($footerLogoPath));
            $footerLogoBase64 = 'data:'.$mime.';base64,'.$data;
        }

        $footerHtml = '
        <div style="width: 100%; text-align: center; margin-bottom: 5mm; -webkit-print-color-adjust: exact; font-size: 10px;">
            <img src="'.$footerLogoBase64.'" style="height: 70px; object-fit: contain; margin: 0 auto;" />
        </div>';

        $pdf = \Spatie\Browsershot\Browsershot::html($html)
            ->format('A4')
            ->margins(12.7, 12.7, 25.4, 12.7)
            ->showBrowserHeaderAndFooter()
            ->headerHtml('<div></div>')
            ->footerHtml($footerHtml)
            ->showBackground()
            ->writeOptionsToFile()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Penawaran-'.$requestOrder->request_number.'.pdf"');
    }

    public function edit(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->expired_at && $requestOrder->created_at) {
            try {
                $diffDays = \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->diffInDays($requestOrder->created_at);
                if ($diffDays <= 8 && \Illuminate\Support\Carbon::parse($requestOrder->expired_at)->greaterThan(now())) {
                    $requestOrder->update(['expired_at' => now()->addDays(14)]);
                }
            } catch (\Throwable $e) {
            }
        }

        $requestOrder->loadMissing('customPenawaran', 'items.barang');

        $goods = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $customers = Customer::where('status', 'active')
            ->orderBy('nama_customer')
            ->get();

        $categories = Barang::distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->pluck('kategori')->sort()->values();

        $salesUsers = \App\Models\User::where('role', 'Sales')
            ->orderBy('name')
            ->get();

        return view('admin.sales.request-order.edit', compact('requestOrder', 'goods', 'customers', 'categories', 'salesUsers'))
            ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diupdate!']);
    }

    public function update(Request $request, RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
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
            'barang_id.*' => 'nullable|integer|exists:goods,id',
            'kategori_barang' => 'required|array|min:1',
            'kategori_barang.*' => 'nullable|string|max:100',
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
            'nama_barang_custom' => 'nullable|array',
            'nama_barang_custom.*' => 'nullable|string|max:255',
            'existing_item_images' => 'nullable|array',
            'existing_item_images.*' => 'nullable|array',
            'existing_item_images.*.*' => 'nullable|string',
        ]);

        foreach ($validated['barang_id'] as $i => $barangId) {
            $isCustom = empty($barangId) && (! empty($validated['nama_barang_custom'][$i] ?? null));
            $isRegular = ! empty($barangId);
            if (! $isCustom && ! $isRegular) {
                return back()->withErrors(["barang_id.$i" => 'Pilih barang atau isi nama barang custom pada baris ke-'.($i + 1)])
                    ->withInput();
            }
            if (($isCustom || $isRegular) && empty($validated['kategori_barang'][$i])) {
                return back()->withErrors(["kategori_barang.$i" => 'Kategori barang wajib diisi pada baris ke-'.($i + 1)])
                    ->withInput();
            }
        }

        // =====================================================================
        // AMBIL DATA PENTING SEBELUM ITEMS DIHAPUS
        // Query langsung ke DB agar nilai pasti fresh (bukan dari cache relasi)
        // =====================================================================
        $maxDiskonLama = \App\Models\RequestOrderItem::where('request_order_id', $requestOrder->id)
            ->max('diskon_percent') ?? 0;

        // Ambil order terkait beserta supervisor_id SEBELUM update
        $existingOrder = \App\Models\Order::where('request_order_id', $requestOrder->id)->first();
        $statusSekarang = $existingOrder?->status;

        // "Pernah diapprove" = supervisor_id sudah terisi di order
        // Ini lebih reliable daripada cek status, karena status bisa berubah-ubah
        $pernahDiapprove = ! empty($existingOrder?->supervisor_id);

        // Atau status saat ini memang sudah melewati tahap supervisor
        $sudahApprove = $pernahDiapprove || in_array($statusSekarang, [
            'approved_supervisor',
            'sent_to_warehouse',
            'approved_warehouse',
            'not_completed',
            'completed',
        ]);

        $items = [];
        foreach ($validated['barang_id'] as $i => $barangId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) {
                continue;
            }

            $diskon = isset($validated['diskon_percent'][$i]) && $validated['diskon_percent'][$i] !== '' ? (float) $validated['diskon_percent'][$i] : 0;

            if (empty($barangId)) {
                $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== ''
                    ? (float) $validated['harga'][$i]
                    : 0;
            } else {
                $baseHarga = optional(Barang::find($barangId))->harga ?? 0;
                $computedHarga = round($baseHarga * 1.3, 2);
                $harga = isset($validated['harga'][$i]) && $validated['harga'][$i] !== ''
                    ? (float) $validated['harga'][$i]
                    : $computedHarga;
            }
            $subtotal = round($qty * $harga * (1 - ($diskon / 100)), 2);

            $items[] = [
                'original_index' => $i,
                'barang_id' => empty($barangId) ? null : (int) $barangId,
                'nama_barang_custom' => empty($barangId)
                    ? ($validated['nama_barang_custom'][$i] ?? null)
                    : null,
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

        // Hitung diskon baru dari items yang akan disimpan
        $maxDiskonBaru = collect($items)->max('diskon_percent') ?? 0;

        DB::beginTransaction();
        try {
            $supportingImages = $requestOrder->supporting_images ?? [];
            if ($request->hasFile('supporting_images')) {
                foreach ($request->file('supporting_images') as $file) {
                    $path = $file->store('request-order-images', 'public');
                    $supportingImages[] = $path;
                }
            }

            $headerSubtotal = array_reduce($items, function ($carry, $item) {
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
                'supporting_images' => ! empty($supportingImages) ? $supportingImages : null,
                'subtotal' => $headerSubtotal,
                'tax' => $headerTax,
                'grand_total' => $headerGrandTotal,
            ]);

            $requestOrder->items()->delete();

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
                if (empty($itemImagePaths)) {
                    $existingImgs = $request->input("existing_item_images.{$origIdx}", []);
                    if (! empty($existingImgs)) {
                        $itemImagePaths = array_values(array_filter($existingImgs));
                    }
                }

                $itemData = [
                    'request_order_id' => $requestOrder->id,
                    'barang_id' => $item['barang_id'],
                    'nama_barang_custom' => $item['nama_barang_custom'] ?? null,
                    'kategori_barang' => $item['kategori_barang'] ?? null,
                    'quantity' => $item['quantity'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                    'images' => ! empty($itemImagePaths) ? $itemImagePaths : null,
                ];

                if (Schema::hasColumn('request_order_items', 'diskon_percent')) {
                    $itemData['diskon_percent'] = $item['diskon_percent'] ?? 0;
                }

                RequestOrderItem::create($itemData);
            }

            // =====================================================================
            // LOGIKA STATUS ORDER
            // =====================================================================
            //
            // Skenario yang mungkin terjadi:
            //
            // A. Diskon baru ≤ 20%
            //    → Tidak perlu approve, status = 'open'
            //
            // B. Diskon baru > 20%, dan supervisor SUDAH PERNAH approve sebelumnya,
            //    dan diskon TIDAK baru melewati batas 20% (artinya dulu juga sudah >20%)
            //    → Tidak perlu approve ulang, pertahankan status yang ada
            //    Contoh: diskon 25% sudah diapprove → edit subject/catatan/qty → tidak perlu approve lagi
            //    Contoh: diskon 25% sudah diapprove → edit diskon jadi 30% → TETAP tidak perlu approve
            //            karena supervisor sudah tahu ada diskon tinggi dan sudah approve
            //
            // C. Diskon baru > 20%, dan supervisor BELUM PERNAH approve
            //    → Minta approve supervisor
            //
            // D. Diskon baru > 20%, sebelumnya diskon ≤ 20% (baru pertama kali melampaui batas)
            //    → Minta approve supervisor meskipun sebelumnya pernah approve hal lain
            // =====================================================================

            // Apakah diskon ini baru pertama kali melewati batas 20%?
            $diskonBaruMelampauiBatas = ($maxDiskonLama <= 20 && $maxDiskonBaru > 20);

            if ($maxDiskonBaru <= 20) {
                // Skenario A: diskon aman, tidak perlu approve
                $orderStatus = 'open';
            } elseif ($sudahApprove && ! $diskonBaruMelampauiBatas) {
                // Skenario B: sudah pernah diapprove dan diskon tidak baru melampaui batas
                // → pertahankan status yang ada, tidak perlu approve ulang
                $orderStatus = $statusSekarang;
            } else {
                // Skenario C & D: belum pernah diapprove, atau diskon baru pertama melampaui batas
                $orderStatus = 'sent_to_supervisor';
            }

            if ($existingOrder) {
                $updateData = ['status' => $orderStatus];
                // Reset data supervisor HANYA jika status berubah ke sent_to_supervisor
                if ($orderStatus === 'sent_to_supervisor') {
                    $updateData['supervisor_id'] = null;
                    $updateData['approved_at'] = null;
                    $updateData['reason'] = null;
                }
                $existingOrder->update($updateData);
            } else {
                \App\Models\Order::create([
                    'order_number' => 'ORD-'.strtoupper(\Illuminate\Support\Str::random(8)),
                    'sales_id' => $requestOrder->sales_id,
                    'customer_name' => $requestOrder->customer_name,
                    'customer_id' => $requestOrder->customer_id,
                    'request_order_id' => $requestOrder->id,
                    'status' => $orderStatus,
                    'tanggal_kebutuhan' => $requestOrder->tanggal_kebutuhan,
                    'catatan_customer' => $requestOrder->catatan_customer,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.request-order.show', $requestOrder->id)
                ->with('success', 'Request Order berhasil diubah.')
                ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diubah!']);

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal mengubah Request Order: '.$e->getMessage())
                ->withInput()
                ->with(['title' => 'Gagal', 'text' => 'Gagal mengubah Request Order!']);
        }
    }

    public function sentToWarehouse(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->order) {
            return back()->withErrors('Request Order ini sudah dikirim ke Warehouse.');
        }

        try {
            $this->processSentToWarehouse($requestOrder);

            return redirect()->route('sales.request-order.index')
                ->with('success', 'Request Order berhasil dikirim ke Warehouse.')
                ->with(['title' => 'Berhasil', 'text' => 'Order berhasil dikirim ke Warehouse!']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal mengirim ke Warehouse: '.$e->getMessage())
                ->with(['title' => 'Gagal', 'text' => 'Gagal mengirim ke Warehouse!']);
        }
    }

    public function destroy(RequestOrder $requestOrder)
    {
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($requestOrder) {
                if ($requestOrder->order) {
                    $requestOrder->order->items()->delete();
                    $requestOrder->order->delete();
                }
                $requestOrder->items()->delete();
                $requestOrder->delete();
            });

            return redirect()->route('sales.request-order.index')->with([
                'success' => 'Request Order berhasil dihapus.',
                'title' => 'Terhapus!',
                'text' => 'Request Order dan data terkait telah berhasil dihapus dari sistem.',
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Request Order: '.$e->getMessage())->with([
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mencoba menghapus data.',
            ]);
        }
    }

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

                if ($ro && ! $ro->order) {
                    $this->processSentToWarehouse($ro);
                    $successCount++;
                }
            } catch (\Throwable $e) {
            }
        }

        return response()->json([
            'success' => $successCount > 0,
            'count' => $successCount,
        ]);
    }

    public function uploadImageSO(Request $request, RequestOrder $requestOrder)
    {
        if ($request->hasFile('image_so')) {
            $path = $request->file('image_so')->store('request-order-so-images', 'public');
            if ($requestOrder->image_so) {
                Storage::disk('public')->delete($requestOrder->image_so);
            }
            $requestOrder->image_so = $path;
            $requestOrder->save();

            return response()->json(['status' => 'success', 'image_url' => Storage::url($path)]);
        }

        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function deleteImageSO(RequestOrder $requestOrder)
    {
        if ($requestOrder->image_so) {
            Storage::disk('public')->delete($requestOrder->image_so);
            $requestOrder->image_so = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function uploadImagePO(Request $request, RequestOrder $requestOrder)
    {
        if ($request->hasFile('image_po')) {
            $path = $request->file('image_po')->store('request-order-po-images', 'public');
            if ($requestOrder->image_po) {
                Storage::disk('public')->delete($requestOrder->image_po);
            }
            $requestOrder->image_po = $path;
            $requestOrder->save();

            return response()->json(['status' => 'success', 'image_url' => Storage::url($path)]);
        }

        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function deleteImagePO(RequestOrder $requestOrder)
    {
        if ($requestOrder->image_po) {
            Storage::disk('public')->delete($requestOrder->image_po);
            $requestOrder->image_po = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function uploadPdfPO(Request $request, RequestOrder $requestOrder)
    {
        if ($request->hasFile('pdf_po')) {
            $path = $request->file('pdf_po')->store('request-order-pdf-po', 'public');
            if ($requestOrder->pdf_po) {
                Storage::disk('public')->delete($requestOrder->pdf_po);
            }
            $requestOrder->pdf_po = $path;
            $requestOrder->save();

            return response()->json(['status' => 'success', 'image_url' => Storage::url($path)]);
        }

        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function updateNoPO(Request $request, RequestOrder $requestOrder)
    {
        $validated = $request->validate([
            'no_po' => 'nullable|string|max:255',
        ]);

        $requestOrder->no_po = $validated['no_po'] ?? null;
        $requestOrder->save();

        return response()->json([
            'status' => 'success',
            'message' => 'No.PO berhasil disimpan',
            'no_po' => $requestOrder->no_po,
        ]);
    }

    public function deletePdfPO(RequestOrder $requestOrder)
    {
        if ($requestOrder->pdf_po) {
            Storage::disk('public')->delete($requestOrder->pdf_po);
            $requestOrder->pdf_po = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    protected function processSentToWarehouse(RequestOrder $ro)
    {
        DB::transaction(function () use ($ro) {
            $existingOrder = Order::where('request_order_id', $ro->id)->first();

            if ($existingOrder) {
                $existingOrder->update([
                    'status' => 'sent_to_warehouse',
                    'do_number' => $existingOrder->do_number
                        ?? ('DO-'.strtoupper(Str::random(8))),
                ]);

                if ($existingOrder->items()->count() === 0) {
                    foreach ($ro->items as $reqItem) {
                        OrderItem::create([
                            'order_id' => $existingOrder->id,
                            'barang_id' => $reqItem->barang_id,
                            'quantity' => $reqItem->quantity,
                            'delivered_quantity' => 0,
                            'status_item' => 'pending',
                            'harga' => $reqItem->harga,
                            'subtotal' => $reqItem->subtotal,
                        ]);
                    }
                }
            } else {
                $order = Order::create([
                    'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                    'do_number' => 'DO-'.strtoupper(Str::random(8)),
                    'sales_id' => Auth::id(),
                    'supervisor_id' => $ro->approved_by ?? null,
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
                        'delivered_quantity' => 0,
                        'status_item' => 'pending',
                        'harga' => $reqItem->harga,
                        'subtotal' => $reqItem->subtotal,
                    ]);
                }
            }
        });
    }
}
