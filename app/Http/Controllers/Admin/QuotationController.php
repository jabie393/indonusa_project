<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class QuotationController extends Controller
{
    public function index()
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('quotations', 'expired_at')) {
            \Illuminate\Support\Facades\DB::table('quotations')
                ->whereNotNull('created_at')
                ->whereNotNull('expired_at')
                ->where('expired_at', '>', now())
                ->whereRaw('TIMESTAMPDIFF(DAY, created_at, expired_at) <= 8')
                ->where('sales_id', Auth::id())
                ->update(['expired_at' => \Illuminate\Support\Facades\DB::raw('DATE_ADD(NOW(), INTERVAL 14 DAY)')]);
        }

        $query = Quotation::with(['items.barang', 'sales', 'pic', 'order.items.barang'])
            ->where('sales_id', Auth::id());

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('quotation_number', 'like', "%{$search}%")
                    ->orWhere('sales_order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('customer_notes', 'like', "%{$search}%")
                    ->orWhere('product_category', 'like', "%{$search}%")
                    ->orWhere('grand_total', 'like', "%{$search}%")
                    ->orWhereHas('pic', function ($picQuery) use ($search) {
                        $picQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('position', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $requestOrders = $query->latest()
            ->paginate(request('perPage', 20))
            ->withQueryString();

        return view('admin.quotation.index', compact('requestOrders'));
    }

    public function create()
    {
        $goods = Barang::where('request_type', 'primary')
            ->where('goods_status', 'approved')
            ->where('stock', '>', 0)
            ->orderBy('goods_name')
            ->get();

        $customers = Customer::where('status', 'active')
            ->with('pics')
            ->orderBy('customer_name')
            ->get();

        $categories = Barang::distinct()
            ->where('goods_status', 'approved')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category')
            ->sort()
            ->values();

        return view('admin.quotation.action.create', compact('goods', 'customers', 'categories'))
            ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil dibuat!']);
    }

    public function store(Request $request)
    {
        // Trim no_po sebelum validasi untuk menghindari duplikat dengan whitespace berbeda
        if ($request->filled('no_po')) {
            $request->merge(['no_po' => trim($request->input('no_po'))]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'pic_id' => 'required|integer|exists:pics,id',
            'subject' => 'required|string|max:255',
            'no_po' => 'nullable|string|max:255|unique:quotations,no_po',
            'required_date' => 'nullable|date',
            'customer_notes' => 'nullable|string',
            'goods_id' => 'required|array|min:1',
            'goods_id.*' => 'nullable',
            'product_category' => 'required|array|min:1',
            'product_category.*' => 'required|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'price' => 'nullable|array',
            'price.*' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|array',
            'discount_percent.*' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'supporting_images' => 'nullable|array',
            'supporting_images.*' => 'nullable|image|max:5120',
            'item_images' => 'nullable|array',
            'item_images.*' => 'nullable|array',
            'item_images.*.*' => 'nullable|image|max:5120',
            'custom_product_name' => 'nullable|array',
            'custom_product_name.*' => 'nullable|string|max:255',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ], [
            'no_po.unique' => 'No. PO sudah digunakan pada quotation lain.',
        ]);

        $items = [];
        $maxDiskon = 0;
        foreach ($validated['goods_id'] as $i => $productId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) {
                continue;
            }

            $baseHarga = $productId ? (optional(Barang::where('goods_status', 'approved')->find($productId))->selling_price ?? 0) : 0;
            $diskon = isset($validated['discount_percent'][$i]) && $validated['discount_percent'][$i] !== '' ? (float) $validated['discount_percent'][$i] : 0;
            $computedHargaSatuan = round($baseHarga * 1.3, 2);
            $hargaSatuan = isset($validated['price'][$i]) && $validated['price'][$i] !== '' ? (float) $validated['price'][$i] : $computedHargaSatuan;
            $subtotal = round($qty * $hargaSatuan * (1 - ($diskon / 100)), 2);

            $items[] = [
                'original_index' => $i,
                'goods_id' => $productId,
                'custom_product_name' => empty($productId) ? ($validated['custom_product_name'][$i] ?? null) : null,
                'product_category' => $validated['product_category'][$i] ?? null,
                'quantity' => $qty,
                'price' => $hargaSatuan,
                'discount_percent' => $diskon,
                'subtotal' => $subtotal,
                'notes' => $validated['keterangan'][$i] ?? null,
            ];
            if ($diskon > $maxDiskon) {
                $maxDiskon = $diskon;
            }
        }

        DB::beginTransaction();
        try {
            // Pengecekan PPN dari tax_rate inputan sales
            $ppnPercent = isset($validated['tax_rate']) ? (float) $validated['tax_rate'] : 11;

            // Hitung total dengan diskon
            $subtotalSum = collect($items)->sum('subtotal');
            $taxAmount = round($subtotalSum * ($ppnPercent / 100), 2);
            $grandTotal = $subtotalSum + $taxAmount;

            $nomorQuotation = Quotation::generateQuotationNumber();
            $tanggalBerlaku = now()->addDays(14); // valid for 14 days

            $requestOrder = Quotation::create([
                'request_number' => 'REQ-' . strtoupper(Str::random(8)),
                'quotation_number' => $nomorQuotation,
                'sales_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'pic_id' => $validated['pic_id'],
                'subject' => $validated['subject'],
                'no_po' => $validated['no_po'] ?? null,
                'required_date' => $validated['required_date'],
                'valid_date' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'customer_notes' => $validated['customer_notes'] ?? null,
                'subtotal' => $subtotalSum,
                'tax' => $taxAmount,
                'grand_total' => $grandTotal,
                'ppn_percent' => $ppnPercent,
                'sales_order_number' => Quotation::generateSalesOrderNumber(),
            ]);

            // Save supporting images
            if ($request->hasFile('supporting_images')) {
                $supportingImagePaths = [];
                foreach ($request->file('supporting_images') as $file) {
                    if ($file) {
                        $supportingImagePaths[] = $file->store('request-order-supporting-images', 'public');
                    }
                }
                $requestOrder->supporting_images = $supportingImagePaths;
                $requestOrder->save();
            }

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
                    'quotation_id' => $requestOrder->id,
                    'goods_id' => $item['goods_id'],
                    'custom_product_name' => $item['custom_product_name'],
                    'product_category' => $item['product_category'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                    'notes' => $item['notes'] ?? null,
                ];

                QuotationItem::create($itemData);
            }

            $requestOrder->refresh();
            $requestOrder->load('items');
            $maxDiskon = $requestOrder->items->max('discount_percent') ?? 0;

            $orderStatus = $maxDiskon > 20 ? 'sent_to_supervisor' : 'open';

            $existingOrder = Order::where('quotation_id', $requestOrder->id)->first();
            if (!$existingOrder) {
                Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'sales_id' => $requestOrder->sales_id,
                    'customer_name' => $requestOrder->customer_name,
                    'customer_id' => $requestOrder->customer_id,
                    'quotation_id' => $requestOrder->id,
                    'status' => $orderStatus,
                    'required_date' => $requestOrder->required_date,
                    'customer_notes' => $requestOrder->customer_notes,
                ]);
            } else {
                $existingOrder->update(['status' => $orderStatus]);
            }

            DB::commit();

            if ($requestOrder->sales_id == Auth::id()) {
                return redirect()->route('sales.quotation.show', $requestOrder->id)
                    ->with('success', "Quotation {$requestOrder->quotation_number} berhasil dibuat.")
                    ->with(['title' => 'Berhasil', 'text' => "Quotation {$requestOrder->quotation_number} berhasil dibuat."]);
            } else {
                return redirect()->route('sales.quotation.index')
                    ->with('success', "Quotation {$requestOrder->quotation_number} berhasil dibuat untuk sales lain.")
                    ->with(['title' => 'Berhasil', 'text' => "Quotation {$requestOrder->quotation_number} berhasil dibuat untuk sales lain."]);
            }

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors('Gagal membuat Quotation: ' . $e->getMessage())
                ->withInput()
                ->with(['title' => 'Gagal', 'text' => 'Gagal membuat Quotation!']);
        }
    }

    public function show(Quotation $quotation)
    {
        $requestOrder = $quotation;
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Warehouse', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        if ($userRole === 'supervisor' && request()->route()->getName() !== 'admin.quotation-approval.show') {
            return redirect()->route('admin.quotation-approval.show', $quotation->id);
        }

        if ($userRole !== 'supervisor' && request()->route()->getName() === 'admin.quotation-approval.show') {
            return redirect()->route('sales.quotation.show', $quotation->id);
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

        return view('admin.quotation-detail.index', compact('requestOrder'));
    }

    public function pdf(Quotation $quotation)
    {
        $requestOrder = $quotation;
        $requestOrder->loadMissing('items', 'order');
        if (!$requestOrder->canDownloadPdf()) {
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

        $pdfNote = request()->query('pdf_note', $requestOrder->customer_notes ?? null);

        $html = view('admin.pdf.quotation', compact('requestOrder', 'pdfNote'))->render();

        $footerLogoPath = public_path('images/footer_logo.png');
        $footerLogoBase64 = '';
        if (file_exists($footerLogoPath)) {
            $mime = mime_content_type($footerLogoPath);
            $data = base64_encode(file_get_contents($footerLogoPath));
            $footerLogoBase64 = 'data:' . $mime . ';base64,' . $data;
        }

        $footerHtml = '
        <div style="width: 100%; text-align: center; margin-bottom: 5mm; -webkit-print-color-adjust: exact; font-size: 10px;">
            <img src="' . $footerLogoBase64 . '" style="height: 70px; object-fit: contain; margin: 0 auto;" />
        </div>';

        $pdf = $this->getBrowsershot($html)
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
            ->header('Content-Disposition', 'inline; filename="Quotation-' . $requestOrder->request_number . '.pdf"');
    }

    public function edit(Quotation $quotation)
    {
        $requestOrder = $quotation;
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

        $requestOrder->loadMissing('customQuotation', 'items.barang');

        $goods = Barang::where('request_type', 'primary')
            ->where('goods_status', 'approved')
            ->where('stock', '>', 0)
            ->orderBy('goods_name')
            ->get();

        $customers = Customer::where('status', 'active')
            ->with('pics')
            ->orderBy('customer_name')
            ->get();

        $categories = Barang::distinct()->where('goods_status', 'approved')->whereNotNull('category')->where('category', '!=', '')->pluck('category')->sort()->values();

        return view('admin.quotation.action.edit', compact('requestOrder', 'goods', 'customers', 'categories'))
            ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil diupdate!']);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $requestOrder = $quotation;
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        // Trim no_po sebelum validasi untuk menghindari duplikat dengan whitespace berbeda
        if ($request->filled('no_po')) {
            $request->merge(['no_po' => trim($request->input('no_po'))]);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_id' => 'nullable|integer',
            'pic_id' => 'required|integer|exists:pics,id',
            'subject' => 'required|string|max:255',
            'no_po' => 'nullable|string|max:255|unique:quotations,no_po,' . $requestOrder->id,
            'sales_order_number' => 'nullable|string|max:255',
            'required_date' => 'nullable|date',
            'customer_notes' => 'nullable|string',
            'goods_id' => 'required|array|min:1',
            'goods_id.*' => 'nullable|integer|exists:goods,id',
            'product_category' => 'required|array|min:1',
            'product_category.*' => 'nullable|string|max:100',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|integer|min:1',
            'price' => 'nullable|array',
            'price.*' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|array',
            'discount_percent.*' => 'nullable|numeric|min:0|max:100',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'supporting_images' => 'nullable|array',
            'supporting_images.*' => 'nullable|image|max:5120',
            'item_images' => 'nullable|array',
            'item_images.*' => 'nullable|array',
            'item_images.*.*' => 'nullable|image|max:5120',
            'custom_product_name' => 'nullable|array',
            'custom_product_name.*' => 'nullable|string|max:255',
            'existing_item_images' => 'nullable|array',
            'existing_item_images.*' => 'nullable|array',
            'existing_item_images.*.*' => 'nullable|string',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ], [
            'no_po.unique' => 'No. PO sudah digunakan pada quotation lain.',
        ]);

        foreach ($validated['goods_id'] as $i => $productId) {
            $isCustom = empty($productId) && (!empty($validated['custom_product_name'][$i] ?? null));
            $isRegular = !empty($productId);
            if (!$isCustom && !$isRegular) {
                return back()->withErrors(["goods_id.$i" => 'Pilih barang atau isi nama barang custom pada baris ke-' . ($i + 1)])
                    ->withInput();
            }
            if (($isCustom || $isRegular) && empty($validated['product_category'][$i])) {
                return back()->withErrors(["product_category.$i" => 'Kategori barang wajib diisi pada baris ke-' . ($i + 1)])
                    ->withInput();
            }
        }

        // =====================================================================
        // AMBIL DATA PENTING SEBELUM ITEMS DIHAPUS
        // Query langsung ke DB agar nilai pasti fresh (bukan dari cache relasi)
        // =====================================================================
        $maxDiskonLama = \App\Models\QuotationItem::where('quotation_id', $requestOrder->id)
            ->max('discount_percent') ?? 0;

        // Ambil order terkait beserta supervisor_id SEBELUM update
        $existingOrder = \App\Models\Order::where('quotation_id', $requestOrder->id)->first();
        $statusSekarang = $existingOrder?->status;

        // "Pernah diapprove" = supervisor_id sudah terisi di order
        // Ini lebih reliable daripada cek status, karena status bisa berubah-ubah
        $pernahDiapprove = !empty($existingOrder?->supervisor_id);

        // Atau status saat ini memang sudah melewati tahap supervisor
        $sudahApprove = $pernahDiapprove || in_array($statusSekarang, [
            'approved_supervisor',
            'sent_to_warehouse',
            'approved_warehouse',
            'not_completed',
            'completed',
        ]);

        $items = [];
        foreach ($validated['goods_id'] as $i => $productId) {
            $qty = (int) $validated['quantity'][$i];
            if ($qty <= 0) {
                continue;
            }

            $diskon = isset($validated['discount_percent'][$i]) && $validated['discount_percent'][$i] !== '' ? (float) $validated['discount_percent'][$i] : 0;

            if (empty($productId)) {
                $harga = isset($validated['price'][$i]) && $validated['price'][$i] !== ''
                    ? (float) $validated['price'][$i]
                    : 0;
            } else {
                $baseHarga = optional(Barang::where('goods_status', 'approved')->find($productId))->selling_price ?? 0;
                $computedHarga = round($baseHarga * 1.3, 2);
                $harga = isset($validated['price'][$i]) && $validated['price'][$i] !== ''
                    ? (float) $validated['price'][$i]
                    : $computedHarga;
            }
            $subtotal = round($qty * $harga * (1 - ($diskon / 100)), 2);

            $items[] = [
                'original_index' => $i,
                'goods_id' => empty($productId) ? null : (int) $productId,
                'custom_product_name' => empty($productId)
                    ? ($validated['custom_product_name'][$i] ?? null)
                    : null,
                'product_category' => $validated['product_category'][$i] ?? null,
                'quantity' => $qty,
                'price' => $harga,
                'discount_percent' => $diskon,
                'subtotal' => $subtotal,
                'notes' => $validated['keterangan'][$i] ?? null,
            ];
        }

        if (empty($items)) {
            return back()->withErrors('Tidak ada item valid.')->withInput();
        }

        // Hitung diskon baru dari items yang akan disimpan
        $maxDiskonBaru = collect($items)->max('discount_percent') ?? 0;

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
                'pic_id' => $validated['pic_id'],
                'customer_name' => $validated['customer_name'],
                'customer_id' => $validated['customer_id'] ?? null,
                'subject' => $validated['subject'],
                'no_po' => $validated['no_po'] ?? null,
                'sales_order_number' => $validated['sales_order_number'] ?? null,
                'product_category' => isset($validated['product_category'][0]) ? $validated['product_category'][0] : null,
                'required_date' => $validated['required_date'] ?? null,
                'customer_notes' => $validated['customer_notes'] ?? null,
                'supporting_images' => !empty($supportingImages) ? $supportingImages : null,
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
                    if (!empty($existingImgs)) {
                        $itemImagePaths = array_values(array_filter($existingImgs));
                    }
                }

                $itemData = [
                    'quotation_id' => $requestOrder->id,
                    'goods_id' => $item['goods_id'],
                    'custom_product_name' => $item['custom_product_name'] ?? null,
                    'product_category' => $item['product_category'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'images' => !empty($itemImagePaths) ? $itemImagePaths : null,
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'notes' => $item['notes'] ?? null,
                ];

                QuotationItem::create($itemData);
            }

            // =====================================================================
            // LOGIKA STATUS ORDER
            // =====================================================================
            $diskonBaruMelampauiBatas = ($maxDiskonLama <= 20 && $maxDiskonBaru > 20);

            if ($maxDiskonBaru <= 20) {
                $orderStatus = 'open';
            } elseif ($sudahApprove && !$diskonBaruMelampauiBatas) {
                $orderStatus = $statusSekarang;
            } else {
                $orderStatus = 'sent_to_supervisor';
            }

            if ($existingOrder) {
                $updateData = ['status' => $orderStatus];
                if ($orderStatus === 'sent_to_supervisor') {
                    $updateData['supervisor_id'] = null;
                    $updateData['approved_at'] = null;
                    $updateData['reason'] = null;
                }
                $existingOrder->update($updateData);
            } else {
                \App\Models\Order::create([
                    'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'sales_id' => $requestOrder->sales_id,
                    'customer_name' => $requestOrder->customer_name,
                    'customer_id' => $requestOrder->customer_id,
                    'quotation_id' => $requestOrder->id,
                    'status' => $orderStatus,
                    'required_date' => $requestOrder->required_date,
                    'customer_notes' => $requestOrder->customer_notes,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with('success', 'Quotation berhasil diubah.')
                ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil diubah!']);

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal mengubah Quotation: ' . $e->getMessage())
                ->withInput()
                ->with(['title' => 'Gagal', 'text' => 'Gagal mengubah Quotation!']);
        }
    }

    public function sentToWarehouse(Quotation $quotation)
    {
        $requestOrder = $quotation;
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($requestOrder->order && in_array($requestOrder->order->status, ['sent_to_warehouse', 'approved_warehouse', 'completed'])) {
            return back()->withErrors('Quotation ini sudah dikirim ke Warehouse.');
        }

        try {
            $this->processSentToWarehouse($requestOrder);

            return redirect()->route('sales.quotation.index')
                ->with('success', 'Quotation berhasil dikirim ke Warehouse.')
                ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil dikirim ke Warehouse!']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal mengirim ke Warehouse: ' . $e->getMessage())
                ->with(['title' => 'Gagal', 'text' => 'Gagal mengirim ke Warehouse!']);
        }
    }

    public function destroy(Quotation $quotation)
    {
        $requestOrder = $quotation;
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

            return redirect()->route('sales.quotation.index')->with([
                'success' => 'Quotation berhasil dihapus.',
                'title' => 'Terhapus!',
                'text' => 'Quotation dan data terkait telah berhasil dihapus dari sistem.',
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Quotation: ' . $e->getMessage())->with([
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
            $requestOrders = Quotation::whereIn('id', $ids)
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
                $ro = Quotation::where('id', $id)
                    ->where('sales_id', Auth::id())
                    ->first();

                if ($ro && (!$ro->order || !in_array($ro->order->status, ['sent_to_warehouse', 'approved_warehouse', 'completed']))) {
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

    public function uploadImageSO(Request $request, Quotation $quotation)
    {
        $requestOrder = $quotation;
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

    public function deleteImageSO(Quotation $quotation)
    {
        $requestOrder = $quotation;
        if ($requestOrder->image_so) {
            Storage::disk('public')->delete($requestOrder->image_so);
            $requestOrder->image_so = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function uploadImagePO(Request $request, Quotation $quotation)
    {
        $requestOrder = $quotation;
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

    public function deleteImagePO(Quotation $quotation)
    {
        $requestOrder = $quotation;
        if ($requestOrder->image_po) {
            Storage::disk('public')->delete($requestOrder->image_po);
            $requestOrder->image_po = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    public function uploadPdfPO(Request $request, Quotation $quotation)
    {
        $requestOrder = $quotation;
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

    public function updateNoPO(Request $request, Quotation $quotation)
    {
        $requestOrder = $quotation;
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

    public function deletePdfPO(Quotation $quotation)
    {
        $requestOrder = $quotation;
        if ($requestOrder->pdf_po) {
            Storage::disk('public')->delete($requestOrder->pdf_po);
            $requestOrder->pdf_po = null;
            $requestOrder->save();
        }

        return response()->json(['status' => 'success']);
    }

    protected function processSentToWarehouse(Quotation $ro)
    {
        DB::transaction(function () use ($ro) {
            $ro->load('items', 'sales');
            $customQuotation = $ro->customQuotation;

            // 1. Check if there are any custom items in this quotation
            $hasCustomItems = false;
            foreach ($ro->items as $item) {
                if (is_null($item->goods_id) && !empty($item->custom_product_name)) {
                    $hasCustomItems = true;
                    break;
                }
            }

            // 3. Process goods creation for custom items
            $goodsMap = []; // maps item ID to created goods model
            foreach ($ro->items as $item) {
                if (is_null($item->goods_id) && !empty($item->custom_product_name)) {
                    // Generate unique goods code
                    $category = $item->product_category ?: 'OTHER CATEGORIES';
                    $generatedCode = \App\Models\Barang::generateUniqueKodeBarang($category);

                    // Find description and unit from CustomQuotationItem
                    $cqItem = null;
                    if ($customQuotation) {
                        $cqItem = $customQuotation->items()
                            ->where('product_name', $item->custom_product_name)
                            ->first();
                    }
                    $unit = $cqItem ? $cqItem->unit : 'pcs';
                    $description = $cqItem ? $cqItem->description : '-';

                    // Create the goods record
                    $goods = \App\Models\Barang::create([
                        'request_type' => 'primary',
                        'goods_status' => 'pending',
                        'status_listing' => 'non_listing',
                        'goods_code' => $generatedCode,
                        'goods_name' => $item->custom_product_name,
                        'category' => $category,
                        'stock' => 0,
                        'unit' => $unit,
                        'buy_price' => 0.00,
                        'selling_price' => 0.00,
                        'form' => $ro->sales_id ?? Auth::id(),
                        'description' => $description,
                    ]);

                    // Update QuotationItem and CustomQuotationItem
                    $item->update(['goods_id' => $goods->id]);
                    if ($cqItem) {
                        $cqItem->update(['goods_id' => $goods->id]);
                    }

                    $goodsMap[$item->id] = $goods->id;
                }
            }

            $existingOrder = Order::where('quotation_id', $ro->id)->first();

            if ($existingOrder) {
                $existingOrder->update([
                    'status' => 'sent_to_warehouse',
                    'do_number' => $existingOrder->do_number
                        ?? ('DO-' . strtoupper(Str::random(8))),
                    'custom_quotation_id' => $ro->custom_quotation_id ?? $existingOrder->custom_quotation_id,
                ]);

                // Sync goods_id to existing order items if they were null
                foreach ($existingOrder->items as $orderItem) {
                    if (is_null($orderItem->goods_id) && !empty($orderItem->custom_product_name)) {
                        // Find matching QuotationItem to get the goods_id
                        $matchedQItem = $ro->items()
                            ->where('custom_product_name', $orderItem->custom_product_name)
                            ->first();
                        if ($matchedQItem && $matchedQItem->goods_id) {
                            $orderItem->update(['goods_id' => $matchedQItem->goods_id]);
                        }
                    }
                }

                if ($existingOrder->items()->count() === 0) {
                    foreach ($ro->items as $reqItem) {
                        $goodsId = $reqItem->goods_id ?? ($goodsMap[$reqItem->id] ?? null);
                        OrderItem::create([
                            'order_id' => $existingOrder->id,
                            'goods_id' => $goodsId,
                            'custom_product_name' => $reqItem->custom_product_name,
                            'category' => $reqItem->product_category,
                            'quantity' => $reqItem->quantity,
                            'delivered_quantity' => 0,
                            'item_status' => 'pending',
                            'price' => $reqItem->price,
                            'subtotal' => $reqItem->subtotal,
                        ]);
                    }
                }
            } else {
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'do_number' => 'DO-' . strtoupper(Str::random(8)),
                    'sales_id' => Auth::id(),
                    'supervisor_id' => $ro->approved_by ?? null,
                    'quotation_id' => $ro->id,
                    'custom_quotation_id' => $ro->custom_quotation_id ?? null,
                    'status' => 'sent_to_warehouse',
                    'customer_name' => $ro->customer_name,
                    'customer_id' => $ro->customer_id,
                    'required_date' => $ro->required_date,
                    'customer_notes' => $ro->customer_notes,
                ]);

                foreach ($ro->items as $reqItem) {
                    $goodsId = $reqItem->goods_id ?? ($goodsMap[$reqItem->id] ?? null);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'goods_id' => $goodsId,
                        'custom_product_name' => $reqItem->custom_product_name,
                        'category' => $reqItem->product_category,
                        'quantity' => $reqItem->quantity,
                        'delivered_quantity' => 0,
                        'item_status' => 'pending',
                        'price' => $reqItem->price,
                        'subtotal' => $reqItem->subtotal,
                    ]);
                }
            }
        });
    }
}
