<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestOrder;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.barang')
            ->where('sales_id', Auth::id())
            ->latest()
            ->paginate(20);

        // 🟢 ubah lokasi view ke folder admin
        return view('admin.requestorder.index', compact('orders'));
    }

    public function create()
    {
        // 1) Semua barang
        // $barangs = Barang::orderBy('nama_barang')->get();

        // 2) Hanya barang yang listing dan stok > 0 (rekomendasi)
        $barangs = Barang::where('tipe_request', 'primary')
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        // 🟢 ubah lokasi view ke folder admin
        return view('admin.requestorder.create', compact('barangs'));
    }

    public function store(StoreRequestOrder $request)
    {
        $data = $request->validated();

        $items = [];
        foreach ($data['barang_id'] as $i => $id) {
            $qty = (int) $data['quantity'][$i];
            if ($qty <= 0) continue;
            $items[$id] = ($items[$id] ?? 0) + $qty;
        }

        if (empty($items)) {
            return back()->withErrors('Tidak ada item valid.')->withInput();
        }

        $insufficient = [];

        DB::beginTransaction();
        try {
            // 🔒 kunci stok supaya tidak bentrok
            foreach ($items as $barangId => $qty) {
                $barang = Barang::where('id', $barangId)->lockForUpdate()->first();
                if (! $barang) {
                    throw new \Exception("Barang ID {$barangId} tidak ditemukan.");
                }
                if ($barang->stok < $qty) {
                    $insufficient[$barangId] = [
                        'nama' => $barang->nama_barang,
                        'stok' => $barang->stok,
                        'req' => $qty,
                    ];
                }
            }

            // 🧾 buat data order utama
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(6)),
                'sales_id' => Auth::id(),
                'status' => 'pending',
                'customer_name' => $data['customer_name'] ?? null,
                'customer_id' => $data['customer_id'] ?? null,
                'tanggal_kebutuhan' => $data['tanggal_kebutuhan'] ?? null,
                'catatan_customer' => $data['catatan'] ?? null,
            ]);

            // 💾 simpan tiap item ke tabel order_items
            foreach ($items as $barangId => $qty) {
                $barang = Barang::find($barangId);
                $status_item = $barang->stok >= $qty ? 'pending' : 'pending_stock';

                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $barangId,
                    'quantity' => $qty,
                    'delivered_quantity' => 0,
                    'status_item' => $status_item,
                ]);
            }

            DB::commit();

            $msg = "Order {$order->order_number} berhasil dibuat.";
            if ($insufficient) {
                $msg .= " Beberapa item stok kurang (pending_stock).";
            }

            return redirect()->route('requestorder.index')->with('success', $msg);

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat order: '.$e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        // 🔐 pastikan hanya sales pemilik / Supervisor / warehouse yang boleh lihat
        if ($order->sales_id !== Auth::id() && ! in_array(Auth::user()->role, ['Supervisor','Warehouse'])) {
            abort(403);
        }

        $order->load('items.barang', 'sales');

        // 🟢 ubah lokasi view ke folder admin
        return view('admin.requestorder.show', compact('order'));
    }
}
