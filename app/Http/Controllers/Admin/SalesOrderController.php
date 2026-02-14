<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\CustomPenawaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        
        // Query untuk Sales Orders
        $soQuery = SalesOrder::where('sales_id', Auth::id())
            ->with('items', 'requestOrder', 'customer');

        if ($request->filled('search')) {
            $soQuery->where(function ($q) use ($search) {
                $q->where('sales_order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('catatan_customer', 'like', '%' . $search . '%');
            });
        }

        // Query untuk Custom Penawarans (juga tampil sebagai SO jika search)
        $penawaranQuery = null;
        if ($request->filled('search')) {
            $penawaranQuery = CustomPenawaran::where('sales_id', Auth::id())
                ->where(function ($q) use ($search) {
                    $q->where('penawaran_number', 'like', '%' . $search . '%')
                      ->orWhere('to', 'like', '%' . $search . '%')
                      ->orWhere('subject', 'like', '%' . $search . '%');
                })
                ->with('items')
                ->get()
                ->map(function ($penawaran) {
                    // Transform penawaran menjadi array yang mirip SO
                    return [
                        'id' => $penawaran->id,
                        'type' => 'penawaran',
                        'sales_order_number' => $penawaran->penawaran_number,
                        'customer_name' => $penawaran->to,
                        'catatan_customer' => $penawaran->subject,
                        'tanggal_kebutuhan' => $penawaran->date,
                        'status' => $penawaran->status,
                        'date' => $penawaran->date,
                        'email' => $penawaran->email,
                        'up' => $penawaran->up,
                        'items' => $penawaran->items,
                        'is_penawaran' => true,
                    ];
                });
        }

        $salesOrders = $soQuery->latest()->paginate(20)->appends(request()->query());

        return view('admin.sales.sales-order.index', compact('salesOrders', 'search', 'penawaranQuery'));
    }

    /**
     * Search Sales Orders via AJAX (including Penawarans)
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        // Search dalam Sales Orders
        $soResults = SalesOrder::where('sales_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('sales_order_number', 'like', '%' . $query . '%')
                  ->orWhere('customer_name', 'like', '%' . $query . '%')
                  ->orWhere('catatan_customer', 'like', '%' . $query . '%');
            })
            ->with('items', 'requestOrder', 'customer')
            ->limit(5)
            ->get()
            ->map(function ($so) {
                return [
                    'id' => $so->id,
                    'type' => 'sales_order',
                    'sales_order_number' => $so->sales_order_number,
                    'customer_name' => $so->customer_name,
                    'catatan_customer' => $so->catatan_customer,
                    'tanggal_kebutuhan' => $so->tanggal_kebutuhan ? \Carbon\Carbon::parse($so->tanggal_kebutuhan)->format('d/m/Y') : '-',
                    'status' => $so->status,
                    'url' => route('sales.sales-order.show', $so),
                    'badge' => 'Sales Order',
                ];
            });

        // Search dalam Custom Penawarans
        $penawaranResults = CustomPenawaran::where('sales_id', Auth::id())
            ->where(function ($q) use ($query) {
                $q->where('penawaran_number', 'like', '%' . $query . '%')
                  ->orWhere('to', 'like', '%' . $query . '%')
                  ->orWhere('subject', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->with('items')
            ->limit(5)
            ->get()
            ->map(function ($penawaran) {
                return [
                    'id' => $penawaran->id,
                    'type' => 'penawaran',
                    'sales_order_number' => $penawaran->penawaran_number,
                    'customer_name' => $penawaran->to,
                    'catatan_customer' => $penawaran->subject,
                    'email' => $penawaran->email,
                    'up' => $penawaran->up,
                    'tanggal_kebutuhan' => $penawaran->date ? \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') : '-',
                    'status' => $penawaran->status,
                    'url' => route('sales.custom-penawaran.show', $penawaran),
                    'badge' => 'Penawaran',
                ];
            });

        // Gabung hasil
        $results = collect($soResults)->merge($penawaranResults)->all();

        return response()->json([
            'success' => true,
            'data' => $results,
        ]);
    }

    /**
     * Get Penawaran detail via AJAX
     */
    public function getPenawaranDetail(Request $request)
    {
        $penawaranId = $request->input('id');
        
        $penawaran = CustomPenawaran::where('sales_id', Auth::id())
            ->findOrFail($penawaranId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $penawaran->id,
                'penawaran_number' => $penawaran->penawaran_number,
                'to' => $penawaran->to,
                'up' => $penawaran->up,
                'email' => $penawaran->email,
                'subject' => $penawaran->subject,
                'our_ref' => $penawaran->our_ref,
                'date' => $penawaran->date ? \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') : '-',
                'intro_text' => $penawaran->intro_text,
                'status' => $penawaran->status,
                'subtotal' => $penawaran->subtotal,
                'tax' => $penawaran->tax,
                'grand_total' => $penawaran->grand_total,
                'items' => $penawaran->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'qty' => $item->qty,
                        'satuan' => $item->satuan,
                        'harga' => $item->harga,
                        'diskon' => $item->diskon,
                        'subtotal' => $item->subtotal,
                        'keterangan' => $item->keterangan,
                        'images' => $item->images ?? [],
                    ];
                }),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customPenawarans = CustomPenawaran::where('sales_id', Auth::id())
            ->whereIn('status', ['open', 'approved'])
            ->with('items')
            ->latest()
            ->get();
        
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;
        
        return view('admin.sales.sales-order.create', compact('customPenawarans', 'salesUsers', 'currentUserName'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Sales Order Store Request Incoming', [
            'auth_id' => Auth::id(),
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
            'custom_penawaran_id' => 'nullable|integer|exists:custom_penawarans,id',
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
            $salesOrder = SalesOrder::create([
                'sales_id' => Auth::id(),
                'so_number' => SalesOrder::generateSONumber(),
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'our_ref' => $validated['our_ref'] ?? SalesOrder::generateUniqueRef(),
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
                'status' => 'pending_approval',
                'custom_penawaran_id' => $validated['custom_penawaran_id'] ?? null,
            ]);

            Log::info('Sales Order Created', ['id' => $salesOrder->id, 'sales_id' => $salesOrder->sales_id]);

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];
                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('sales-order-images', 'public');
                        }
                    }
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                    'quantity' => $itemData['qty'],
                ]);
            }

            $salesOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            Log::info('Sales Order Commit Successful', ['id' => $salesOrder->id]);

            return redirect()->route('sales.sales-order.show', $salesOrder->id)
                ->with(['title' => 'Berhasil', 'text' => "Sales Order {$salesOrder->so_number} berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Sales Order Store Error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors('Gagal membuat Sales Order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesOrder $salesOrder)
    {
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($salesOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        $salesOrder->load('items', 'sales', 'customPenawaran');
        return view('admin.sales.sales-order.show', compact('salesOrder'));
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesOrder->load('items');
        $salesUsers = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;
        
        return view('admin.sales.sales-order.edit', compact('salesOrder', 'salesUsers', 'currentUserName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
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
            $salesOrder->update([
                'to' => $validated['to'],
                'up' => $validated['up'],
                'subject' => $validated['subject'],
                'email' => $validated['email'],
                'date' => $validated['date'],
                'intro_text' => $validated['intro_text'] ?? null,
                'tax' => $validated['tax'] ?? 0,
            ]);

            $salesOrder->items()->delete();

            $subtotal = 0;
            foreach ($validated['items'] as $i => $itemData) {
                $itemImages = [];

                if ($request->hasFile("items.$i.images")) {
                    foreach ($request->file("items.$i.images") as $file) {
                        if ($file) {
                            $itemImages[] = $file->store('sales-order-images', 'public');
                        }
                    }
                }

                if (empty($itemImages) && isset($validated['items'][$i]['existing_images']) && !empty($validated['items'][$i]['existing_images'])) {
                    $itemImages = $validated['items'][$i]['existing_images'];
                }

                $itemSubtotal = $itemData['qty'] * $itemData['harga'] * (1 - ($itemData['diskon'] ?? 0) / 100);
                $subtotal += $itemSubtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $salesOrder->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'qty' => $itemData['qty'],
                    'satuan' => $itemData['satuan'],
                    'harga' => $itemData['harga'],
                    'subtotal' => $itemSubtotal,
                    'diskon' => $itemData['diskon'] ?? 0,
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'images' => !empty($itemImages) ? $itemImages : null,
                    'quantity' => $itemData['qty'],
                ]);
            }

            $salesOrder->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal + ($validated['tax'] ?? 0),
            ]);

            DB::commit();

            return redirect()->route('sales.sales-order.show', $salesOrder->id)
                ->with(['title' => 'Berhasil', 'text' => 'Sales Order berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Sales Order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesOrder $salesOrder)
    {
        if ($salesOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($salesOrder) {
                $salesOrder->items()->delete();
                $salesOrder->delete();
            });
            return redirect()->route('sales.sales-order.index')
                ->with(['title' => 'Berhasil', 'text' => 'Sales Order berhasil dihapus.']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Sales Order: ' . $e->getMessage());
        }
    }
}
