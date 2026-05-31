<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Http\Controllers\Controller;
use App\Models\RequestOrder;
use App\Models\RequestOrderItem;
use App\Models\CustomPenawaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Barang;
use App\Models\DeliveryBatch;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceExport;
use App\Exports\GaSalesOrderExport;

class SalesOrderController extends Controller
{
    /**
     * Helper private: mapping RequestOrder ke array row tabel
     */
    private function mapRequestOrderRow(\App\Models\RequestOrder $ro): array
    {
        $diskonPersen = ($ro->items && $ro->items->count() > 0)
            ? ($ro->items->first()->diskon_percent ?? 0) : 0;

        $berlakuSampai = '-';
        if ($ro->tanggal_berlaku) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->tanggal_berlaku)->translatedFormat('d F Y');
        } elseif ($ro->expired_at) {
            $berlakuSampai = \Carbon\Carbon::parse($ro->expired_at)->translatedFormat('d F Y');
        }

        $firstPic = $ro->customer?->pics?->first();

        return [
            'id'             => $ro->id,
            'type'           => 'request_order',
            'no_request'     => $ro->request_number,
            'no_penawaran'   => $ro->nomor_penawaran,
            'no_po'          => $ro->no_po ?? '-',
            'no_sales_order' => $ro->sales_order_number,
            'tanggal'        => $ro->tanggal_kebutuhan ? $ro->tanggal_kebutuhan->format('d/m/Y') : '-',
            'customer_name'  => $ro->customer_name,
            'first_pic_name' => $firstPic?->name,
            'first_pic_position' => $firstPic?->position,
            'jumlah_item'    => $ro->items->count(),
            'total'          => $ro->grand_total ?? 0,
            'diskon'         => $diskonPersen,
            'status'         => $ro->status,
            'berlaku_sampai' => $berlakuSampai,
            'image_po'       => $ro->image_po,
            'pdf_po'         => $ro->pdf_po,
            'customer_status'=> $ro->customer->status ?? 'active',
            'aksi_url'       => '#',
            'has_batches'    => $ro->order && $ro->order->batches->isNotEmpty(),
        ];
    }



    /**
     * Kirim RequestOrder ke Warehouse dari halaman SO
     */
    public function sentRequestOrderToWarehouse(Request $request, \App\Models\RequestOrder $quotation)
    {
        $alreadySent = Order::where('request_order_id', $quotation->id)
            ->whereIn('status', ['sent_to_warehouse', 'completed', 'not_completed'])
            ->exists();

        if ($alreadySent) {
            return redirect()->back()
                ->with(['title' => 'Gagal', 'text' => 'Request Order ini sudah pernah dikirim ke warehouse.']);
        }

        DB::beginTransaction();
        try {
            $quotation->load('items', 'sales');
            $existingOrder = Order::where('request_order_id', $quotation->id)->first();

            if ($existingOrder) {
                $doNumber = $existingOrder->do_number ?? ('DO-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                ));
                $existingOrder->update(['status' => 'sent_to_warehouse', 'do_number' => $doNumber]);
                $orderNumber = $existingOrder->order_number;
            } else {
                $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                );
                $doNumber = 'DO-' . now()->format('Ymd') . '-' . str_pad(
                    Order::whereDate('created_at', now()->toDateString())->count() + 1,
                    4, '0', STR_PAD_LEFT
                );

                $order = Order::create([
                    'order_number'        => $orderNumber,
                    'do_number'           => $doNumber,
                    'sales_id'            => $quotation->sales_id ?? Auth::id(),
                    'customer_name'       => $quotation->customer_name,
                    'customer_id'         => $quotation->customer_id ?? null,
                    'request_order_id'    => $quotation->id,
                    'custom_penawaran_id' => $quotation->custom_penawaran_id ?? null,
                    'status'              => 'sent_to_warehouse',
                    'tanggal_kebutuhan'   => $quotation->tanggal_kebutuhan ?? now()->toDateString(),
                    'catatan_customer'    => $quotation->catatan_customer ?? null,
                ]);

                foreach ($quotation->items as $item) {
                    OrderItem::create([
                        'order_id'           => $order->id,
                        'barang_id'          => $item->barang_id ?? null,
                        'quantity'           => $item->quantity ?? 1,
                        'delivered_quantity' => 0,
                        'status_item'        => 'pending',
                        'harga'              => $item->harga ?? 0,
                        'subtotal'           => $item->subtotal ?? 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()
                ->with(['title' => 'Berhasil', 'text' => "Request Order berhasil dikirim ke Warehouse dengan No. {$orderNumber}."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors('Gagal mengirim ke warehouse: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $results  = collect();
        $isSearch = $request->filled('search');
        $perPage  = (int) $request->input('perPage', 20);

        if ($isSearch) {
            $results = \App\Models\RequestOrder::where(function ($q) use ($search) {
                    $q->where('request_number',     'like', "%$search%")
                      ->orWhere('nomor_penawaran',   'like', "%$search%")
                      ->orWhere('sales_order_number','like', "%$search%")
                      ->orWhere('customer_name',     'like', "%$search%")
                      ->orWhere('no_po',             'like', "%$search%");
                })
                ->where('sales_id', Auth::id())
                ->with(['order.batches', 'items', 'customer.pics'])
                ->get()
                ->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                    'catatan_customer' => $ro->catatan_customer,
                    'aksi_url'         => route('sales.quotation.show', $ro),
                    'image_po'         => $ro->image_po,
                ]));
        } else {
            $requestOrders = \App\Models\RequestOrder::where('sales_id', Auth::id())
                ->with(['order.batches', 'items', 'customer.pics'])
                ->latest()
                ->paginate($perPage)
                ->appends(request()->query());

            $results = $requestOrders->map(fn($ro) => array_merge($this->mapRequestOrderRow($ro), [
                'catatan_customer' => $ro->catatan_customer,
                'aksi_url'         => route('sales.quotation.show', $ro),
                'image_po'         => $ro->image_po,
            ]));

            $salesOrders = $requestOrders;
        }

        return view('admin.sales-order.index', [
            'results'     => $results,
            'search'      => $search,
            'isSearch'    => $isSearch,
            'salesOrders' => isset($salesOrders) ? $salesOrders : null,
        ]);
    }

    public function getPenawaranDetail(Request $request)
    {
        $penawaranId = $request->input('id');
        $penawaran   = CustomPenawaran::where('sales_id', Auth::id())->findOrFail($penawaranId);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'               => $penawaran->id,
                'penawaran_number' => $penawaran->penawaran_number,
                'to'               => $penawaran->to,
                'up'               => $penawaran->up,
                'email'            => $penawaran->email,
                'subject'          => $penawaran->subject,
                'our_ref'          => $penawaran->our_ref,
                'date'             => $penawaran->date ? \Carbon\Carbon::parse($penawaran->date)->format('d/m/Y') : '-',
                'intro_text'       => $penawaran->intro_text,
                'status'           => $penawaran->status,
                'subtotal'         => $penawaran->subtotal,
                'tax'              => $penawaran->tax,
                'grand_total'      => $penawaran->grand_total,
                'items'            => $penawaran->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->nama_barang,
                        'qty'         => $item->qty,
                        'satuan'      => $item->satuan,
                        'harga'       => $item->harga,
                        'diskon'      => $item->diskon,
                        'subtotal'    => $item->subtotal,
                        'keterangan'  => $item->keterangan,
                        'images'      => $item->images ?? [],
                    ];
                }),
            ],
        ]);
    }

    public function create()
    {
        $customPenawarans = CustomPenawaran::where('sales_id', Auth::id())
            ->whereIn('status', ['open', 'approved'])
            ->with('items')
            ->latest()
            ->get();

        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.sales-order.create', compact('customPenawarans', 'salesUsers', 'currentUserName'));
    }

    public function store(Request $request)
    {
        Log::info('Sales Order Store Request Incoming (Pivoted to RequestOrder)', [
            'auth_id' => Auth::id(),
            'request' => $request->all(),
        ]);

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to'                  => 'required|string|max:255',
            'up'                  => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject'             => 'required|string|max:255',
            'email'               => 'required|email',
            'date'                => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.harga'       => 'required|numeric|min:0',
            'items.*.satuan'      => 'required|string|max:50',
            'items.*.diskon'      => 'nullable|integer|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $requestOrder = RequestOrder::create([
                'sales_id'          => Auth::id(),
                'request_number'    => RequestOrder::generateNomorPenawaran(),
                'customer_name'     => $validated['to'],
                'subject'           => $validated['subject'],
                'tanggal_kebutuhan' => $validated['date'],
                'status'            => 'pending',
            ]);

            foreach ($validated['items'] as $itemData) {
                RequestOrderItem::create([
                    'request_order_id'  => $requestOrder->id,
                    'nama_barang_custom'=> $itemData['nama_barang'],
                    'quantity'          => $itemData['qty'],
                    'harga'             => $itemData['harga'],
                    'subtotal'          => $itemData['qty'] * $itemData['harga'],
                    'diskon_percent'    => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => "Request Order berhasil dibuat."]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Request Order Store Error (from SO Controller)', ['message' => $e->getMessage()]);
            return back()->withErrors('Gagal membuat Request Order: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $requestOrder = RequestOrder::with('items.barang', 'sales', 'customPenawaran', 'order', 'customer')->findOrFail($id);

        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed  = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($requestOrder->sales_id !== Auth::id() && !in_array($userRole, $allowed)) {
            abort(403);
        }

        return view('admin.quotation.action.show', compact('requestOrder'));
    }

    public function edit($id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $requestOrder->load('items');
        $salesUsers      = User::where('role', 'Sales')->pluck('name', 'name')->toArray();
        $currentUserName = Auth::user()->name;

        return view('admin.quotation.action.edit', compact('requestOrder', 'salesUsers', 'currentUserName'));
    }

    public function update(Request $request, $id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        $salesNames = User::where('role', 'Sales')->pluck('name')->toArray();

        $validated = $request->validate([
            'to'                  => 'required|string|max:255',
            'up'                  => ['required', 'string', 'max:255', Rule::in($salesNames)],
            'subject'             => 'required|string|max:255',
            'email'               => 'required|email',
            'date'                => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.nama_barang' => 'required|string|max:255',
            'items.*.qty'         => 'required|integer|min:1',
            'items.*.harga'       => 'required|numeric|min:0',
            'items.*.satuan'      => 'required|string|max:50',
            'items.*.diskon'      => 'nullable|integer|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $requestOrder->update([
                'customer_name'     => $validated['to'],
                'subject'           => $validated['subject'],
                'tanggal_kebutuhan' => $validated['date'],
            ]);

            $requestOrder->items()->delete();

            foreach ($validated['items'] as $itemData) {
                RequestOrderItem::create([
                    'request_order_id'   => $requestOrder->id,
                    'nama_barang_custom' => $itemData['nama_barang'],
                    'quantity'           => $itemData['qty'],
                    'harga'              => $itemData['harga'],
                    'subtotal'           => $itemData['qty'] * $itemData['harga'],
                    'diskon_percent'     => $itemData['diskon'] ?? 0,
                ]);
            }

            DB::commit();
            return redirect()->route('sales.quotation.show', $requestOrder->id)
                ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil diubah.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal mengubah Request Order: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($requestOrder) {
                $requestOrder->items()->delete();
                $requestOrder->delete();
            });
            return redirect()->route('sales.quotation.index')
                ->with(['title' => 'Berhasil', 'text' => 'Request Order berhasil dihapus.']);
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus Request Order: ' . $e->getMessage());
        }
    }

    public function uploadImage(Request $request, $id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('request-order-so-images', 'public');
            $requestOrder->image_so = $path;
            $requestOrder->save();
            return response()->json(['status' => 'success', 'image_url' => \Illuminate\Support\Facades\Storage::url($path)]);
        }
        return response()->json(['status' => 'error', 'message' => 'No file uploaded']);
    }

    public function deleteImage($id)
    {
        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder->sales_id !== Auth::id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($requestOrder->image_so) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($requestOrder->image_so);
            $requestOrder->image_so = null;
            $requestOrder->save();
        }
        return response()->json(['status' => 'success']);
    }

    public function search(Request $request)
    {
        $search = $request->input('q', '');
        if (empty($search)) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $results = \App\Models\RequestOrder::where('sales_id', Auth::id())
            ->where(function ($q) use ($search) {
                $q->where('request_number',  'like', "%$search%")
                  ->orWhere('nomor_penawaran','like', "%$search%")
                  ->orWhere('customer_name',  'like', "%$search%")
                  ->orWhere('no_po',          'like', "%$search%");
            })
            ->limit(10)
            ->get()
            ->map(function ($ro) {
                return [
                    'sales_order_number' => $ro->nomor_penawaran ?: ($ro->request_number ?: 'Quotation'),
                    'customer_name'      => $ro->customer_name,
                    'type'               => 'penawaran',
                    'badge'              => 'Quotation',
                    'url'                => route('sales.quotation.show', $ro->id),
                    'no_po'              => $ro->no_po,
                ];
            });

        return response()->json(['success' => true, 'data' => $results]);
    }
} // ← penutup class SalesOrderController