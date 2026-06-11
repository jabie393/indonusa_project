<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomQuotation;
use App\Models\CustomQuotationItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CustomQuotationController extends Controller
{
    /**
     * List semua custom quotations milik sales
     */
    public function index(Request $request)
    {
        try {
            if (
                \Illuminate\Support\Facades\Schema::hasTable('custom_quotations') &&
                \Illuminate\Support\Facades\Schema::hasColumn('custom_quotations', 'expired_at')
            ) {

                CustomQuotation::whereIn('status', ['open', 'sent'])
                    ->whereNotNull('expired_at')
                    ->where('expired_at', '<', now())
                    ->where('sales_id', Auth::id())
                    ->update(['status' => 'expired']);
            }
        } catch (\Throwable $e) {
            Log::warning('Custom Quotation Expiry update failed: ' . $e->getMessage());
        }

        $query = CustomQuotation::where('sales_id', Auth::id())
            ->with('items')
            ->latest();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('our_ref', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('up', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('perPage', 10);
        $customQuotations = $query->paginate($perPage)->withQueryString();

        return view('admin.custom-quotation.index', compact('customQuotations'));
    }

    /**
     * Form untuk membuat custom quotation baru
     */
    public function create()
    {
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.custom-quotation.action.create', compact('salesUsers', 'currentUserName'));
    }

    /**
     * Simpan custom quotation baru
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging (include authenticated user)
        Log::info('Custom Quotation Store Request Incoming', [
            'auth_id' => Auth::id(),
            'auth_email' => Auth::user()->email ?? null,
            'request' => $request->all(),
            'items_count' => count($request->input('items', [])),
        ]);

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'our_ref' => 'nullable|string|max:255',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.category' => 'required|string|in:' . implode(',', \App\Models\Barang::KATEGORI),
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|string|max:50',
            'items.*.diskon' => 'required|integer|min:0|max:100',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $quotation = CustomQuotation::create([
                'sales_id' => Auth::id(),
                'quotation_number' => CustomQuotation::generateQuotationNumber(),
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'our_ref' => $validated['our_ref'] ?? CustomQuotation::generateUniqueRef(),
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
                'status' => 'pending_approval',
            ]);

            // Set expired_at to 14 days from created_at
            $quotation->update(['expired_at' => $quotation->created_at->addDays(14)]);

            // Log created quotation id
            Log::info('Custom Quotation Created', ['id' => $quotation->id, 'sales_id' => $quotation->sales_id]);

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('custom-quotation-images', 'public');
                        }
                    }
                }

                $rawHarga = str_replace(',', '', $itemData['harga']);
                $hargaFloat = (float) $rawHarga;

                $itemSubtotal = $itemData['qty'] * $hargaFloat * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomQuotationItem::create([
                    'custom_quotation_id' => $quotation->id,
                    'product_name' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'unit' => $itemData['satuan'],
                    'price' => $hargaFloat,
                    'subtotal' => $itemSubtotal,
                    'discount' => $itemData['diskon'] ?? 0,
                    'description' => $itemData['description'] ?? null,
                    'notes' => $itemData['keterangan'] ?? null,
                    'category' => $itemData['category'],
                    // Pastikan images selalu array dan path tanpa awalan 'public/'
                    'images' => !empty($itemImages) ? array_map(function ($img) {
                        return str_replace('public/', '', $img);
                    }, $itemImages) : null,
                ]);
            }

            $quotation->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            // Log commit confirmation
            Log::info('Custom Quotation Commit Successful', ['id' => $quotation->id]);

            return redirect()->route('sales.custom-quotation.show', $quotation->id)
                ->with(['title' => 'Berhasil', 'text' => "Quotation {$quotation->quotation_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Quotation Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->withErrors('Gagal membuat quotation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail custom quotation
     */
    public function show(CustomQuotation $customQuotation)
    {
        // Allow owner (Sales) or Supervisor/Admin to view the quotation
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customQuotation->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        if ($userRole === 'supervisor' && request()->route()->getName() !== 'admin.custom-quotation-approval.show') {
            return redirect()->route('admin.custom-quotation-approval.show', $customQuotation->id);
        }

        if ($userRole !== 'supervisor' && request()->route()->getName() === 'admin.custom-quotation-approval.show') {
            return redirect()->route('sales.custom-quotation.show', $customQuotation->id);
        }

        $customQuotation->load('items', 'sales');

        return view('admin.custom-quotation-detail.index', compact('customQuotation'));
    }

    /**
     * Form edit custom quotation
     */
    public function edit(CustomQuotation $customQuotation)
    {
        if ($customQuotation->sales_id !== Auth::id()) {
            abort(403);
        }

        $customQuotation->load('items');
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.custom-quotation.action.edit', compact('customQuotation', 'salesUsers', 'currentUserName'));
    }

    /**
     * Update custom quotation
     */
    public function update(Request $request, CustomQuotation $customQuotation)
    {
        if ($customQuotation->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'up' => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'intro_text' => 'nullable|string',
            'tax' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.category' => 'required|string|in:' . implode(',', \App\Models\Barang::KATEGORI),
            'items.*.qty' => 'required|integer|min:1',
            'items.*.satuan' => 'required|string|max:50',
            'items.*.harga' => 'required|string|max:50',
            'items.*.diskon' => 'required|integer|min:0|max:100',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
            'items.*.existing_images' => 'nullable|array',
            'items.*.existing_images.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $customQuotation->update([
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
            ]);

            // Get existing items before deleting so we have a reference
            $existingItems = $customQuotation->items()->get();
            $customQuotation->items()->delete();

            $needApproval = false;
            foreach ($validated['items'] as $i => $itemData) {
                if ($itemData['diskon'] > 20) {
                    $needApproval = true;
                    if (empty($itemData['keterangan'])) {
                        throw new \Exception('Keterangan wajib diisi jika diskon lebih dari 20%.');
                    }
                }
            }

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];

                // Check if new images were uploaded for this item
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('custom-quotation-images', 'public');
                        }
                    }
                }

                // If no new images, use existing images from the validated data
                if (empty($itemImages) && isset($validated['items'][$i]['existing_images']) && !empty($validated['items'][$i]['existing_images'])) {
                    $itemImages = $validated['items'][$i]['existing_images'];
                }

                $rawHarga = str_replace(',', '', $itemData['harga']);
                $hargaFloat = (float) $rawHarga;

                $itemSubtotal = $itemData['qty'] * $hargaFloat * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomQuotationItem::create([
                    'custom_quotation_id' => $customQuotation->id,
                    'product_name' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'unit' => $itemData['satuan'],
                    'price' => $hargaFloat,
                    'subtotal' => $itemSubtotal,
                    'discount' => $itemData['diskon'] ?? 0,
                    'description' => $itemData['description'] ?? null,
                    'notes' => $itemData['keterangan'] ?? null,
                    'category' => $itemData['category'],
                    'images' => !empty($itemImages) ? array_map(function ($img) {
                        return str_replace('public/', '', $img);
                    }, $itemImages) : null,
                ]);
            }

            $updateData = [
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ];

            if (!in_array($customQuotation->status, ['approved', 'approved_supervisor'])) {
                $updateData = array_merge($updateData, [
                    'status' => 'pending_approval',
                    'approved_by' => null,
                    'approved_at' => null,
                    'reason' => null,
                ]);
            }

            $customQuotation->update($updateData);

            DB::commit();

            return redirect()->route('sales.custom-quotation.show', $customQuotation->id)
                ->with(['title' => 'Berhasil', 'text' => 'Quotation berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal mengubah quotation: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus custom quotation
     */
    public function destroy(CustomQuotation $customQuotation)
    {
        if ($customQuotation->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($customQuotation) {
                // If there is an associated order, delete it
                if ($customQuotation->order) {
                    $customQuotation->order->items()->delete();
                    $customQuotation->order->delete();
                }

                // If there is an associated request order, delete it
                if ($customQuotation->quotation) {
                    $customQuotation->quotation->items()->delete();
                    $customQuotation->quotation->delete();
                }

                // Delete custom quotation items
                $customQuotation->items()->delete();

                // Delete the custom quotation itself
                $customQuotation->delete();
            });

            return redirect()->route('sales.custom-quotation.index')->with([
                'success' => 'Quotation berhasil dihapus.',
                'title' => 'Terhapus!',
                'text' => 'Quotation Kustom dan data terkait telah berhasil dihapus dari sistem.',
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus quotation: ' . $e->getMessage())->with([
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mencoba menghapus data.',
            ]);
        }
    }

    public function pdf(CustomQuotation $customQuotation)
    {
        // Only owner (Sales) or Supervisor/Admin can view, but enforce approval rule:
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customQuotation->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        // PDF dikunci jika status pending_approval atau rejected_supervisor, kecuali Supervisor/Admin
        if (in_array($customQuotation->status, ['pending_approval', 'rejected_supervisor'])) {
            if (!in_array($userRole, $allowed)) {
                return back()->withErrors('PDF tidak dapat diunduh karena status saat ini pending_approval atau rejected_supervisor.');
            }
        }

        $html = view('admin.pdf.custom-quotation-pdf', compact('customQuotation'))->render();

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
            ->header('Content-Disposition', 'inline; filename="Custom-Quotation-' . $customQuotation->quotation_number . '.pdf"');
    }

    /**
     * Sent Custom Quotation to Warehouse (create Order with status sent_to_warehouse)
     */
    public function sentToWarehouse(CustomQuotation $customQuotation)
    {
        // Allow if user is admin or the sales who created it
        if (Auth::user()->role !== 'Admin' && $customQuotation->sales_id !== Auth::id()) {
            abort(403);
        }

        if ($customQuotation->status !== 'ready_for_delivery') {
            return back()->withErrors('Hanya Custom Quotation yang berstatus Ready for Delivery yang dapat dikirim ke Warehouse.');
        }

        if ($customQuotation->order) {
            return back()->withErrors('Custom Quotation ini sudah dikirim ke Warehouse.');
        }

        if ($customQuotation->items->isEmpty()) {
            return back()->withErrors('Custom Quotation tidak memiliki item.');
        }

        DB::beginTransaction();
        try {
            Log::info('Starting sentToWarehouse for custom quotation ID: ' . $customQuotation->id);

            $order = Order::create([
                'order_number' => 'DO-' . strtoupper(Str::random(8)),
                'sales_id' => Auth::id(),
                'supervisor_id' => $customQuotation->status === 'approved' ? Auth::id() : null,
                'custom_quotation_id' => $customQuotation->id,
                'status' => 'sent_to_warehouse',
                'customer_name' => $customQuotation->to,
                'customer_id' => null,
                'required_date' => $customQuotation->date,
                'customer_notes' => $customQuotation->intro_text,
            ]);

            Log::info('Order created with ID: ' . $order->id);

            foreach ($customQuotation->items as $item) {
                if (is_null($item->price) || is_null($item->subtotal) || $item->qty <= 0) {
                    throw new \Exception('Item data invalid: price, subtotal, or qty is invalid for item ID ' . $item->id);
                }
                Log::info('Creating OrderItem for item ID: ' . $item->id . ', qty: ' . $item->qty . ', price: ' . $item->price . ', subtotal: ' . $item->subtotal);
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $item->goods_id,
                    'quantity' => $item->qty,
                    'harga' => $item->price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            Log::info('OrderItems created');

            // Update Custom Quotation status to sent_to_warehouse
            $customQuotation->update(['status' => 'sent_to_warehouse']);

            Log::info('Custom quotation status updated');

            DB::commit();

            Log::info('Transaction committed');

            return redirect()->route('sales.custom-quotation.index')
                ->with(['title' => 'Berhasil', 'text' => "Order {$order->order_number} berhasil dikirim ke Warehouse."]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error in sentToWarehouse: ' . $e->getMessage());

            return back()->withErrors('Gagal mengirim ke Warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Hapus Custom Quotations
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        DB::beginTransaction();
        try {
            $quotations = CustomQuotation::whereIn('id', $ids)
                ->where('sales_id', Auth::id())
                ->get();

            foreach ($quotations as $quotation) {
                // Delete associated items first
                $quotation->items()->delete();
                $quotation->delete();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Quotation Bulk Delete Error', ['message' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Gagal menghapus quotation: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk Send to Warehouse for Custom Quotations
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
                $quotation = CustomQuotation::where('id', $id)
                    ->where('sales_id', Auth::id())
                    ->first();

                if ($quotation && $quotation->status === 'ready_for_delivery' && !$quotation->order) {
                    if ($quotation->items->isNotEmpty()) {
                        DB::transaction(function () use ($quotation) {
                            $order = Order::create([
                                'order_number' => 'DO-' . strtoupper(Str::random(8)),
                                'sales_id' => Auth::id(),
                                'supervisor_id' => $quotation->status === 'approved' ? Auth::id() : null,
                                'custom_quotation_id' => $quotation->id,
                                'status' => 'sent_to_warehouse',
                                'customer_name' => $quotation->to,
                                'customer_id' => null,
                                'required_date' => $quotation->date,
                                'customer_notes' => $quotation->intro_text,
                            ]);

                            foreach ($quotation->items as $item) {
                                OrderItem::create([
                                    'order_id' => $order->id,
                                    'barang_id' => $item->goods_id,
                                    'quantity' => $item->qty,
                                    'harga' => $item->price,
                                    'subtotal' => $item->subtotal,
                                ]);
                            }
                            $quotation->update(['status' => 'sent_to_warehouse']);
                        });
                        $successCount++;
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Custom Quotation Bulk Send Error', ['id' => $id, 'message' => $e->getMessage()]);
            }
        }

        if ($successCount > 0) {
            return response()->json(['success' => true, 'count' => $successCount]);
        }

        return response()->json(['success' => false, 'message' => 'No items were processed.']);
    }

    /**
     * Kirim Custom Quotation ke Quotation
     */
    public function sentToQuotation(CustomQuotation $customQuotation)
    {
        if (Auth::user()->role !== 'Admin' && $customQuotation->sales_id !== Auth::id()) {
            abort(403);
        }
        if ($customQuotation->status !== 'open' && $customQuotation->status !== 'approved_supervisor') {
            return back()->withErrors('Hanya quotation dengan status open atau approved supervisor yang bisa dikirim ke Quotation.');
        }
        $existing = Quotation::where('custom_quotation_id', $customQuotation->id)->first();
        if ($existing) {
            return redirect()->route('sales.quotation.show', $existing->id)
                ->with([
                    'title' => 'Info',
                    'text' => 'Sudah pernah dikirim ke Quotation.',
                ]);
        }
        DB::beginTransaction();
        try {
            $nomorQuotation = Quotation::generateQuotationNumber();
            $tanggalBerlaku = now()->addDays(14);
            $requestOrder = Quotation::create([
                'custom_quotation_id' => $customQuotation->id,
                'request_number' => 'REQ-' . strtoupper(Str::random(8)),
                'quotation_number' => $nomorQuotation,
                'sales_id' => $customQuotation->sales_id,
                'customer_name' => $customQuotation->to,
                'subject' => $customQuotation->subject,
                'required_date' => $customQuotation->date,
                'valid_date' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'customer_notes' => $customQuotation->intro_text,
                'subtotal' => $customQuotation->subtotal,
                'tax' => $customQuotation->tax ?? 0,
                'grand_total' => $customQuotation->grand_total,
                'no_po' => null,
                'sales_order_number' => Quotation::generateSalesOrderNumber(),
            ]);
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'sales_id' => $customQuotation->sales_id,
                'quotation_id' => $requestOrder->id,
                'customer_name' => $customQuotation->to,
                'status' => 'open',
                'required_date' => $customQuotation->date,
                'customer_notes' => $customQuotation->intro_text,
            ]);
            foreach ($customQuotation->items as $cpItem) {
                $category = $cpItem->category ?: 'OTHER CATEGORIES';

                // Create QuotationItem (without creating a Barang record yet!)
                QuotationItem::create([
                    'quotation_id' => $requestOrder->id,
                    'goods_id' => null,
                    'custom_product_name' => $cpItem->product_name,
                    'custom_product_description' => $cpItem->description,
                    'custom_product_unit' => $cpItem->unit,
                    'product_category' => $category,
                    'quantity' => $cpItem->qty,
                    'price' => $cpItem->price,
                    'subtotal' => $cpItem->subtotal,
                    'discount_percent' => $cpItem->discount ?? 0,
                    'images' => $cpItem->images,
                    'notes' => $cpItem->notes,
                ]);

                // Create OrderItem (without creating a Barang record yet!)
                OrderItem::create([
                    'order_id' => $order->id,
                    'goods_id' => null,
                    'custom_product_name' => $cpItem->product_name,
                    'category' => $category,
                    'quantity' => $cpItem->qty,
                    'delivered_quantity' => 0,
                    'item_status' => 'pending',
                    'price' => $cpItem->price,
                    'subtotal' => $cpItem->subtotal,
                ]);
            }
            $customQuotation->update(['status' => 'sent_to_quotation']);
            DB::commit();

            // Redirect langsung ke halaman quotation sales
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with([
                    'title' => 'Berhasil',
                    'text' => "Berhasil dikirim ke Quotation: {$requestOrder->request_number}",
                ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal: ' . $e->getMessage());
        }
    }
}
