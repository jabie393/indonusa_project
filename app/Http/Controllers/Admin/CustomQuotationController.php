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
     * List semua custom penawarans milik sales
     */
    public function index(Request $request)
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('custom_quotations') &&
                \Illuminate\Support\Facades\Schema::hasColumn('custom_quotations', 'expired_at')) {

                CustomQuotation::whereIn('status', ['open', 'sent'])
                    ->whereNotNull('expired_at')
                    ->where('expired_at', '<', now())
                    ->where('sales_id', Auth::id())
                    ->update(['status' => 'expired']);
            }
        } catch (\Throwable $e) {
            Log::warning('Custom Quotation Expiry update failed: '.$e->getMessage());
        }

        $query = CustomQuotation::where('sales_id', Auth::id())
            ->with('items')
            ->latest();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('perPage', 10);
        $customPenawarans = $query->paginate($perPage)->withQueryString();

        return view('admin.custom-quotation.index', compact('customPenawarans'));
    }

    /**
     * Form untuk membuat custom penawaran baru
     */
    public function create()
    {
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.custom-quotation.action.create', compact('salesUsers', 'currentUserName'));
    }

    /**
     * Simpan custom penawaran baru
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
            $penawaran = CustomQuotation::create([
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
            $penawaran->update(['expired_at' => $penawaran->created_at->addDays(14)]);

            // Log created penawaran id
            Log::info('Custom Quotation Created', ['id' => $penawaran->id, 'sales_id' => $penawaran->sales_id]);

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('custom-penawaran-images', 'public');
                        }
                    }
                }

                $rawHarga = str_replace(',', '', $itemData['harga']);
                $hargaFloat = (float) $rawHarga;

                $itemSubtotal = $itemData['qty'] * $hargaFloat * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomQuotationItem::create([
                    'custom_quotation_id' => $penawaran->id,
                    'product_name' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'unit' => $itemData['satuan'],
                    'price' => $hargaFloat,
                    'subtotal' => $itemSubtotal,
                    'discount' => $itemData['diskon'] ?? 0,
                    'description' => $itemData['keterangan'] ?? null,
                    // Pastikan images selalu array dan path tanpa awalan 'public/'
                    'images' => ! empty($itemImages) ? array_map(function ($img) {
                        return str_replace('public/', '', $img);
                    }, $itemImages) : null,
                ]);
            }

            $penawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            // Log commit confirmation
            Log::info('Custom Quotation Commit Successful', ['id' => $penawaran->id]);

            return redirect()->route('sales.custom-quotation.show', $penawaran->id)
                ->with(['title' => 'Berhasil', 'text' => "Penawaran {$penawaran->quotation_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Quotation Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return back()->withErrors('Gagal membuat penawaran: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail custom penawaran
     */
    public function show(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        // Allow owner (Sales) or Supervisor/Admin to view the penawaran
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customPenawaran->sales_id !== Auth::id() && ! in_array($userRole, $allowed)) {
            abort(403);
        }

        $customPenawaran->load('items', 'sales');

        return view('admin.custom-quotation.action.show', compact('customPenawaran'));
    }

    /**
     * Form edit custom penawaran
     */
    public function edit(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->load('items');
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.custom-quotation.action.edit', compact('customPenawaran', 'salesUsers', 'currentUserName'));
    }

    /**
     * Update custom penawaran
     */
    public function update(Request $request, CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        if ($customPenawaran->sales_id !== Auth::id()) {
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
            $customPenawaran->update([
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
            ]);

            // Get existing items before deleting so we have a reference
            $existingItems = $customPenawaran->items()->get();
            $customPenawaran->items()->delete();

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
                            $itemImages[] = $file->store('custom-penawaran-images', 'public');
                        }
                    }
                }

                // If no new images, use existing images from the validated data
                if (empty($itemImages) && isset($validated['items'][$i]['existing_images']) && ! empty($validated['items'][$i]['existing_images'])) {
                    $itemImages = $validated['items'][$i]['existing_images'];
                }

                $rawHarga = str_replace(',', '', $itemData['harga']);
                $hargaFloat = (float) $rawHarga;

                $itemSubtotal = $itemData['qty'] * $hargaFloat * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomQuotationItem::create([
                    'custom_quotation_id' => $customPenawaran->id,
                    'product_name' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'unit' => $itemData['satuan'],
                    'price' => $hargaFloat,
                    'subtotal' => $itemSubtotal,
                    'discount' => $itemData['diskon'] ?? 0,
                    'description' => $itemData['keterangan'] ?? null,
                    'images' => ! empty($itemImages) ? array_map(function ($img) {
                        return str_replace('public/', '', $img);
                    }, $itemImages) : null,
                ]);
            }

            $customPenawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
                'status' => 'pending_approval',
                'approved_by' => null,
                'approved_at' => null,
                'reason' => null,
            ]);

            DB::commit();

            return redirect()->route('sales.custom-quotation.show', $customPenawaran->id)
                ->with(['title' => 'Berhasil', 'text' => 'Penawaran berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal mengubah penawaran: '.$e->getMessage())->withInput();
        }
    }

    /**
     * Hapus custom penawaran
     */
    public function destroy(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($customPenawaran) {
                // If there is an associated order, delete it
                if ($customPenawaran->order) {
                    $customPenawaran->order->items()->delete();
                    $customPenawaran->order->delete();
                }

                // If there is an associated request order, delete it
                if ($customPenawaran->quotation) {
                    $customPenawaran->quotation->items()->delete();
                    $customPenawaran->quotation->delete();
                }

                // Delete custom penawaran items
                $customPenawaran->items()->delete();

                // Delete the custom penawaran itself
                $customPenawaran->delete();
            });

            return redirect()->route('sales.custom-quotation.index')->with([
                'success' => 'Penawaran berhasil dihapus.',
                'title' => 'Terhapus!',
                'text' => 'Penawaran Kustom dan data terkait telah berhasil dihapus dari sistem.',
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus penawaran: '.$e->getMessage())->with([
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mencoba menghapus data.',
            ]);
        }
    }

    public function pdf(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        // Only owner (Sales) or Supervisor/Admin can view, but enforce approval rule:
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customPenawaran->sales_id !== Auth::id() && ! in_array($userRole, $allowed)) {
            abort(403);
        }

        // PDF hanya bisa didownload jika status approved_supervisor, kecuali Supervisor/Admin
        if ($customPenawaran->status !== 'approved_supervisor') {
            if (! in_array($userRole, $allowed)) {
                return back()->withErrors('PDF hanya dapat didownload setelah disetujui oleh Supervisor.');
            }
        }

        $html = view('admin.pdf.custom-quotation-pdf', compact('customPenawaran'))->render();

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
            ->header('Content-Disposition', 'inline; filename="Custom-Quotation-'.$customPenawaran->quotation_number.'.pdf"');
    }

    /**
     * Sent Custom Penawaran to Warehouse (create Order with status sent_to_warehouse)
     */
    public function sentToWarehouse(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        // Allow if user is admin or the sales who created it
        if (Auth::user()->role !== 'Admin' && $customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($customPenawaran->status, ['open', 'approved', 'approved_supervisor'])) {
            return back()->withErrors('Hanya Custom Penawaran yang open, approved, atau approved supervisor dapat dikirim ke Warehouse.');
        }

        if ($customPenawaran->order) {
            return back()->withErrors('Custom Penawaran ini sudah dikirim ke Warehouse.');
        }

        if ($customPenawaran->items->isEmpty()) {
            return back()->withErrors('Custom Penawaran tidak memiliki item.');
        }

        DB::beginTransaction();
        try {
            Log::info('Starting sentToWarehouse for custom quotation ID: '.$customPenawaran->id);

            $order = Order::create([
                'order_number' => 'DO-'.strtoupper(Str::random(8)),
                'sales_id' => Auth::id(),
                'supervisor_id' => $customPenawaran->status === 'approved' ? Auth::id() : null,
                'custom_quotation_id' => $customPenawaran->id,
                'status' => 'sent_to_warehouse',
                'customer_name' => $customPenawaran->to,
                'customer_id' => null,
                'required_date' => $customPenawaran->date,
                'customer_notes' => $customPenawaran->intro_text,
            ]);

            Log::info('Order created with ID: '.$order->id);

            foreach ($customPenawaran->items as $item) {
                if (is_null($item->price) || is_null($item->subtotal) || $item->qty <= 0) {
                    throw new \Exception('Item data invalid: price, subtotal, or qty is invalid for item ID '.$item->id);
                }
                Log::info('Creating OrderItem for item ID: '.$item->id.', qty: '.$item->qty.', price: '.$item->price.', subtotal: '.$item->subtotal);
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => null,
                    'quantity' => $item->qty,
                    'harga' => $item->price,
                    'subtotal' => $item->subtotal,
                ]);
            }

            Log::info('OrderItems created');

            // Update Custom Penawaran status to sent_to_warehouse
            $customPenawaran->update(['status' => 'sent_to_warehouse']);

            Log::info('Custom penawaran status updated');

            DB::commit();

            Log::info('Transaction committed');

            return redirect()->route('sales.custom-quotation.index')
                ->with(['title' => 'Berhasil', 'text' => "Order {$order->order_number} berhasil dikirim ke Warehouse."]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error in sentToWarehouse: '.$e->getMessage());

            return back()->withErrors('Gagal mengirim ke Warehouse: '.$e->getMessage());
        }
    }

    /**
     * Bulk Hapus Custom Penawarans
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        DB::beginTransaction();
        try {
            $penawarans = CustomQuotation::whereIn('id', $ids)
                ->where('sales_id', Auth::id())
                ->get();

            foreach ($penawarans as $penawaran) {
                // Delete associated items first
                $penawaran->items()->delete();
                $penawaran->delete();
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Quotation Bulk Delete Error', ['message' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Gagal menghapus penawaran: '.$e->getMessage()]);
        }
    }

    /**
     * Bulk Send to Warehouse for Custom Penawarans
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
                $penawaran = CustomQuotation::where('id', $id)
                    ->where('sales_id', Auth::id())
                    ->first();

                if ($penawaran && in_array($penawaran->status, ['open', 'approved', 'approved_supervisor']) && ! $penawaran->order) {
                    if ($penawaran->items->isNotEmpty()) {
                        DB::transaction(function () use ($penawaran) {
                            $order = Order::create([
                                'order_number' => 'DO-'.strtoupper(Str::random(8)),
                                'sales_id' => Auth::id(),
                                'supervisor_id' => $penawaran->status === 'approved' ? Auth::id() : null,
                                'custom_quotation_id' => $penawaran->id,
                                'status' => 'sent_to_warehouse',
                                'customer_name' => $penawaran->to,
                                'customer_id' => null,
                                'required_date' => $penawaran->date,
                                'customer_notes' => $penawaran->intro_text,
                            ]);

                            foreach ($penawaran->items as $item) {
                                OrderItem::create([
                                    'order_id' => $order->id,
                                    'barang_id' => null,
                                    'quantity' => $item->qty,
                                    'harga' => $item->price,
                                    'subtotal' => $item->subtotal,
                                ]);
                            }
                            $penawaran->update(['status' => 'sent_to_warehouse']);
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
     * Kirim Custom Penawaran ke Penawaran (Request Order)
     */
    public function sentToPenawaran(CustomQuotation $customQuotation)
    {
        $customPenawaran = $customQuotation;
        if (Auth::user()->role !== 'Admin' && $customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }
        if ($customPenawaran->status !== 'open' && $customPenawaran->status !== 'approved_supervisor') {
            return back()->withErrors('Hanya penawaran dengan status open atau approved supervisor yang bisa dikirim ke Penawaran.');
        }
        $existing = Quotation::where('custom_quotation_id', $customPenawaran->id)->first();
        if ($existing) {
            return redirect()->route('sales.quotation.show', $existing->id)
                ->with('info', 'Sudah pernah dikirim ke Penawaran.');
        }
        DB::beginTransaction();
        try {
            $nomorPenawaran = Quotation::generateQuotationNumber();
            $tanggalBerlaku = now()->addDays(14);
            $requestOrder = Quotation::create([
                'custom_quotation_id' => $customPenawaran->id,
                'request_number' => 'REQ-'.strtoupper(Str::random(8)),
                'quotation_number' => $nomorPenawaran,
                'sales_id' => $customPenawaran->sales_id,
                'customer_name' => $customPenawaran->to,
                'subject' => $customPenawaran->subject,
                'required_date' => $customPenawaran->date,
                'valid_date' => $tanggalBerlaku,
                'expired_at' => $tanggalBerlaku,
                'customer_notes' => $customPenawaran->intro_text,
                'subtotal' => $customPenawaran->subtotal,
                'tax' => $customPenawaran->tax ?? 0,
                'grand_total' => $customPenawaran->grand_total,
                'no_po' => null,
                'sales_order_number' => null,
            ]);
            Order::create([
                'order_number' => 'ORD-'.strtoupper(uniqid()),
                'sales_id' => $customPenawaran->sales_id,
                'quotation_id' => $requestOrder->id,
                'customer_name' => $customPenawaran->to,
                'status' => 'open',
                'required_date' => $customPenawaran->date,
                'customer_notes' => $customPenawaran->intro_text,
            ]);
            foreach ($customPenawaran->items as $cpItem) {
                QuotationItem::create([
                    'quotation_id' => $requestOrder->id,
                    'product_id' => null,
                    'custom_product_name' => $cpItem->product_name,
                    'product_category' => $cpItem->description ?? 'Custom',
                    'quantity' => $cpItem->qty,
                    'price' => $cpItem->price,
                    'subtotal' => $cpItem->subtotal,
                    'discount_percent' => $cpItem->discount ?? 0,
                    'images' => $cpItem->images,
                ]);
            }
            $customPenawaran->update(['status' => 'sent_to_penawaran']);
            DB::commit();

            // Redirect langsung ke halaman penawaran sales
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with('success', "Berhasil dikirim ke Penawaran: {$requestOrder->request_number}");
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Gagal: '.$e->getMessage());
        }
    }
}
