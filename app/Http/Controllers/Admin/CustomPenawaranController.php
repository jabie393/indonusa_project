<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomPenawaranController extends Controller
{
    /**
     * List semua custom penawarans milik sales
     */
    public function index()
    {
        // Log current user and items fetched for debugging
        Log::info('Custom Penawaran Index accessed', ['auth_id' => Auth::id(), 'auth_email' => Auth::user()->email ?? null]);

        // Check and update expired penawarans
        CustomPenawaran::whereIn('status', ['open', 'sent'])
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->where('sales_id', Auth::id())
            ->update(['status' => 'expired']);

        $customPenawarans = CustomPenawaran::where('sales_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(20);

        // Also log raw DB table count and sample rows for diagnosis
        try {
            $rawCount = DB::table('custom_penawarans')->count();
            $sample = DB::table('custom_penawarans')->orderByDesc('id')->limit(5)->get();
            Log::info('Custom Penawaran Index result', ['count' => $customPenawarans->count(), 'raw_count' => $rawCount, 'sample' => $sample]);
        } catch (\Throwable $e) {
            Log::error('Custom Penawaran Index DB read error', ['message' => $e->getMessage()]);
        }

        return view('admin.sales.custom-penawaran.index', compact('customPenawarans'));
    }

    /**
     * Form untuk membuat custom penawaran baru
     */
    public function create()
    {
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;
        return view('admin.sales.custom-penawaran.create', compact('salesUsers', 'currentUserName'));
    }

    /**
     * Simpan custom penawaran baru
     */
    public function store(Request $request)
    {
        // Log incoming request for debugging (include authenticated user)
        Log::info('Custom Penawaran Store Request Incoming', [
            'auth_id' => Auth::id(),
            'auth_email' => Auth::user()->email ?? null,
            'request' => $request->all(),
            'items_count' => count($request->input('items', []))
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
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.diskon' => 'required|integer|min:0|max:100',
            'items.*.keterangan' => 'nullable|string|max:255',
            'items.*.images' => 'nullable|array',
            'items.*.images.*' => 'nullable|image|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $needApproval = false;
            foreach ($validated['items'] as $i => $itemData) {
                if ($itemData['diskon'] > 20) {
                    $needApproval = true;
                    if (empty($itemData['keterangan'])) {
                        throw new \Exception('Keterangan wajib diisi jika diskon lebih dari 20%.');
                    }
                }
            }

            $penawaran = CustomPenawaran::create([
                'sales_id' => Auth::id(),
                'penawaran_number' => CustomPenawaran::generatePenawaranNumber(),
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'our_ref' => $validated['our_ref'] ?? CustomPenawaran::generateUniqueRef(),
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
                'status' => $needApproval ? 'sent' : 'open',
            ]);

            // Set expired_at to 14 days from created_at
            $penawaran->update(['expired_at' => $penawaran->created_at->addDays(14)]);

            // Log created penawaran id
            Log::info('Custom Penawaran Created', ['id' => $penawaran->id, 'sales_id' => $penawaran->sales_id]);

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

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomPenawaranItem::create([
                    'custom_penawaran_id' => $penawaran->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                ]);
            }

            $penawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            // Log commit confirmation
            Log::info('Custom Penawaran Commit Successful', ['id' => $penawaran->id]);

            // Simulasi permintaan approval ke Supervisor
            // TODO: Implementasi notifikasi/approval Supervisor

            Log::info('Custom Penawaran Returning Redirect To Show', ['id' => $penawaran->id]);
            return redirect()->route('sales.custom-penawaran.show', $penawaran->id)
                ->with(['title' => 'Berhasil', 'text' => "Penawaran {$penawaran->penawaran_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Penawaran Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors('Gagal membuat penawaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail custom penawaran
     */
    public function show(CustomPenawaran $customPenawaran)
    {
        // Allow owner (Sales) or Supervisor/Admin to view the penawaran
            $userRole = trim(strtolower(Auth::user()->role ?? ''));
            $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
            if ($customPenawaran->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
                abort(403);
            }

        $customPenawaran->load('items', 'sales');
        return view('admin.sales.custom-penawaran.show', compact('customPenawaran'));
    }

    /**
     * Form edit custom penawaran
     */
    public function edit(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->load('items');
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;
        return view('admin.sales.custom-penawaran.edit', compact('customPenawaran', 'salesUsers', 'currentUserName'));
    }

    /**
     * Update custom penawaran
     */
    public function update(Request $request, CustomPenawaran $customPenawaran)
    {
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
            'items.*.harga' => 'required|numeric|min:0',
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
                if (empty($itemImages) && isset($validated['items'][$i]['existing_images']) && !empty($validated['items'][$i]['existing_images'])) {
                    $itemImages = $validated['items'][$i]['existing_images'];
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                CustomPenawaranItem::create([
                    'custom_penawaran_id' => $customPenawaran->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                ]);
            }

            $customPenawaran->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
                'status' => $needApproval ? 'sent' : $customPenawaran->status,
            ]);

            DB::commit();

            return redirect()->route('sales.custom-penawaran.show', $customPenawaran->id)
                ->with(['title' => 'Berhasil', 'text' => 'Penawaran berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah penawaran: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus custom penawaran
     */
    public function destroy(CustomPenawaran $customPenawaran)
    {
        if ($customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        $customPenawaran->delete();
        return redirect()->route('sales.custom-penawaran.index')
            ->with(['title' => 'Berhasil', 'text' => 'Penawaran berhasil dihapus.']);
    }

    /**
     * View PDF penawaran
     */
    /**
     * Supervisor approval/reject penawaran
     */
    public function approval(Request $request, CustomPenawaran $customPenawaran)
    {
        // Role check is already done by route middleware 'role:Supervisor'
        $action = $request->input('action');
            if ($customPenawaran->status !== 'sent') {
                return back()->withErrors('Penawaran tidak dalam status menunggu persetujuan.');
            }
            $userRole = trim(strtolower(Auth::user()->role ?? ''));
            $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
            if ($customPenawaran->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
                abort(403);
            }
        if ($action === 'approve') {
            $customPenawaran->status = 'approved';
            $customPenawaran->save();
            return back()->with(['title' => 'Berhasil', 'text' => 'Penawaran telah disetujui.']);
        } elseif ($action === 'reject') {
            $customPenawaran->status = 'rejected';
            $customPenawaran->save();
            return back()->with(['title' => 'Berhasil', 'text' => 'Penawaran telah ditolak.']);
        }
        return back()->withErrors('Aksi tidak valid.');
    }
    public function pdf(CustomPenawaran $customPenawaran)
    {
        // Only owner (Sales) or Supervisor/Admin can view, but enforce approval rule:
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customPenawaran->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        // If there is any item with diskon > 20% and the penawaran is not approved,
        // disallow PDF generation for Sales users (Supervisor/Admin can still view).
        $hasHighDiscount = $customPenawaran->items()->where('diskon', '>', 20)->exists();
        if ($hasHighDiscount && $customPenawaran->status !== 'approved') {
            // If the current user is Supervisor or Admin allow viewing (they can inspect),
            // otherwise deny/redirect back for Sales until approval.
            if (!in_array($userRole, $allowed)) {
                return back()->withErrors('PDF hanya dapat di-generate setelah permintaan diskon disetujui oleh Supervisor.');
            }
        }

        return view('admin.pdf.custom-penawaran-pdf', compact('customPenawaran'));
    }

    /**
     * Sent Custom Penawaran to Warehouse (create Order with status sent_to_warehouse)
     */
    public function sentToWarehouse(CustomPenawaran $customPenawaran)
    {
        // Allow if user is admin or the sales who created it
        if (Auth::user()->role !== 'Admin' && $customPenawaran->sales_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($customPenawaran->status, ['open', 'approved'])) {
            return back()->withErrors('Hanya Custom Penawaran yang open atau approved dapat dikirim ke Warehouse.');
        }

        if ($customPenawaran->order) {
            return back()->withErrors('Custom Penawaran ini sudah dikirim ke Warehouse.');
        }

        if ($customPenawaran->items->isEmpty()) {
            return back()->withErrors('Custom Penawaran tidak memiliki item.');
        }

        DB::beginTransaction();
        try {
            \Log::info('Starting sentToWarehouse for custom penawaran ID: ' . $customPenawaran->id);

            $order = Order::create([
                'order_number' => 'DO-' . strtoupper(Str::random(8)),
                'sales_id' => Auth::id(),
                'supervisor_id' => $customPenawaran->status === 'approved' ? Auth::id() : null, // assuming if approved, supervisor is current user or something
                'custom_penawaran_id' => $customPenawaran->id,
                'status' => 'sent_to_warehouse',
                'customer_name' => $customPenawaran->to,
                'customer_id' => null, // Custom Penawaran might not have customer_id
                'tanggal_kebutuhan' => $customPenawaran->date,
                'catatan_customer' => $customPenawaran->intro_text,
            ]);

            \Log::info('Order created with ID: ' . $order->id);

            foreach ($customPenawaran->items as $item) {
                if (is_null($item->harga) || is_null($item->subtotal) || $item->qty <= 0) {
                    throw new \Exception('Item data invalid: harga, subtotal, or qty is invalid for item ID ' . $item->id);
                }
                \Log::info('Creating OrderItem for item ID: ' . $item->id . ', qty: ' . $item->qty . ', harga: ' . $item->harga . ', subtotal: ' . $item->subtotal);
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => null, // Custom Penawaran items might not have barang_id
                    'quantity' => $item->qty,
                    'harga' => $item->harga,
                    'subtotal' => $item->subtotal,
                ]);
            }

            \Log::info('OrderItems created');

            // Update Custom Penawaran status to sent_to_warehouse
            $customPenawaran->update(['status' => 'sent_to_warehouse']);

            \Log::info('Custom penawaran status updated');

            DB::commit();

            \Log::info('Transaction committed');

            return redirect()->route('sales.custom-penawaran.index')
                ->with(['title' => 'Berhasil', 'text' => "Order {$order->order_number} berhasil dikirim ke Warehouse."]);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error in sentToWarehouse: ' . $e->getMessage());
            return back()->withErrors('Gagal mengirim ke Warehouse: ' . $e->getMessage());
        }
    }
}
