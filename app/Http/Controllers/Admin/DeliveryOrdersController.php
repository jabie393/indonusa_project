<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryBatch;
use App\Models\DeliveryBatchItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DeliveryOrdersController extends Controller
{
    // Tampilkan daftar orders yang statusnya 'sent_to_warehouse'
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');

        // Baseline query: eager-load relations and filter by status
        $orders = Order::with(['supervisor', 'items.barang', 'customer', 'requestOrder.sales'])
            ->whereIn('status', ['sent_to_warehouse', 'not_completed', 'completed', 'rejected_warehouse'])
            ->orderBy('created_at', 'desc');

        if ($query) {
            $orders = $orders->where(function ($q) use ($query) {
                // If query looks numeric, allow id matching
                if (is_numeric($query)) {
                    $q->orWhere('id', $query);
                }

                // Search by order number or DO number (allow partial matches)
                $q->orWhere('order_number', 'like', "%{$query}%")
                  ->orWhere('do_number', 'like', "%{$query}%");

                // Search supervisor name (common column 'name'). If a 'username' column exists, include it.
                $q->orWhereHas('supervisor', function ($sq) use ($query) {
                    $sq->where('name', 'like', "%{$query}%");
                    if (Schema::hasColumn('users', 'username')) {
                        $sq->orWhere('username', 'like', "%{$query}%");
                    }
                    if (Schema::hasColumn('users', 'email')) {
                        $sq->orWhere('email', 'like', "%{$query}%");
                    }
                });

                // Search by barang name in items relation (barang has 'nama_barang')
                $q->orWhereHas('items.barang', function ($bq) use ($query) {
                    $bq->where('nama_barang', 'like', "%{$query}%");
                });
            });
        }

        $orders = $orders->paginate($perPage)->appends($request->except('page'));

        return view('admin.delivery-orders.index', compact('orders'));
    }

    // Approve order
    protected function processApproval($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        foreach ($order->items as $item) {
            $item->delivered_quantity = $item->quantity;
            $item->status_item = 'delivered';
            $item->save();

            // Deduct stock from goods table
            if ($item->barang) {
                $barang = $item->barang;
                $barang->stok -= $item->quantity;
                $barang->save();

                // Manual history logging for stock deduction
                \App\Models\BarangHistory::create([
                    'barang_id'   => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'kategori'    => $barang->kategori,
                    'stok'        => $barang->stok,
                    'satuan'      => $barang->satuan,
                    'lokasi'      => $barang->lokasi,
                    'harga'       => $barang->harga,
                    'old_status'  => $barang->status_barang,
                    'new_status'  => $barang->status_barang, // status remains 'masuk' or same
                    'changed_by'  => \Illuminate\Support\Facades\Auth::id(),
                    'note'        => 'Stock berkurang (' . $item->quantity . ') karena pengiriman penuh DO: ' . ($order->do_number ?? $order->order_number),
                ]);
            }
        }

        $order->status = 'completed';
        if ($order->delivery_options === null) {
            $order->delivery_options = 'full';
        }
        $order->save();

        // Create a delivery batch for full delivery
        $batch = DeliveryBatch::create([
            'order_id' => $order->id,
            'batch_number' => $order->batches()->count() + 1,
        ]);

        foreach ($order->items as $item) {
            DeliveryBatchItem::create([
                'delivery_batch_id' => $batch->id,
                'order_item_id' => $item->id,
                'quantity_sent' => $item->quantity,
            ]);
        }
    }

    // Approve order
    public function approve(Request $request, $id)
    {
        $this->processApproval($id);
        return redirect()->route('delivery-orders.index')->with(['title' => 'Berhasil', 'text' => 'Order berhasil diapprove.']);
    }

    // Reject order
    public function reject(Request $request, $id)
    {
        $order = Order::with('items.barang')->findOrFail($id);
        
        // Check if there are any delivered items
        $hasDeliveries = $order->items->contains(fn($item) => $item->delivered_quantity > 0);

        if ($hasDeliveries) {
            // Case: Partial Delivery already happened. We just finalize the order.
            // Items with delivered_quantity > 0 are marked as delivered (finalized).
            foreach ($order->items as $item) {
                if ($item->delivered_quantity > 0) {
                    if ($item->delivered_quantity < $item->quantity) {
                        $item->status_item = 'partially_delivered';
                    } else {
                        $item->status_item = 'delivered';
                    }
                } else {
                    $item->status_item = 'cancel';
                }
                $item->save();
            }

            $order->status = 'completed';
            $order->reason = $request->input('reason');
            $order->save();

            $message = 'Order (sebagian) telah dibatalkan sisanya dan ditandai Selesai.';
        } else {
            // Case: No deliveries yet, full rejection
            $order->status = 'rejected_warehouse';
            $order->reason = $request->input('reason');
            $order->save();
            
            $message = 'Order berhasil direject.';
        }

        return redirect()->route('delivery-orders.index')->with(['title' => 'Berhasil', 'text' => $message]);
    }

    protected function processRejection($id)
    {
        // This method might be redundant now as logic is moved to reject()
        // Keeping it empty or removing it if not used elsewhere
    }

    public function partialApprove(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        $itemsData = $request->input('items', []);

        foreach ($order->items as $item) {
            if (isset($itemsData[$item->id])) {
                $sentQuantity = (int) $itemsData[$item->id];
                
                if ($sentQuantity > 0) {
                    $newDeliveredQuantity = $item->delivered_quantity + $sentQuantity;
                    $item->delivered_quantity = $newDeliveredQuantity;

                    // Jika total terkirim sudah mencapai atau melebihi kuantitas pesanan
                    if ($newDeliveredQuantity >= $item->quantity) {
                        $item->status_item = 'delivered';
                    } else {
                        $item->status_item = 'partially_delivered';
                    }
                    
                    $item->save();

                    // Deduct stock from goods table for partial delivery
                    if ($item->barang) {
                        $barang = $item->barang;
                        $barang->stok -= $sentQuantity;
                        $barang->save();

                        // Manual history logging for partial stock deduction
                        \App\Models\BarangHistory::create([
                            'barang_id'   => $barang->id,
                            'kode_barang' => $barang->kode_barang,
                            'nama_barang' => $barang->nama_barang,
                            'kategori'    => $barang->kategori,
                            'stok'        => $barang->stok,
                            'satuan'      => $barang->satuan,
                            'lokasi'      => $barang->lokasi,
                            'harga'       => $barang->harga,
                            'old_status'  => $barang->status_barang,
                            'new_status'  => $barang->status_barang,
                            'changed_by'  => \Illuminate\Support\Facades\Auth::id(),
                            'note'        => 'Stock berkurang (' . $sentQuantity . ') karena pengiriman parsial DO: ' . ($order->do_number ?? $order->order_number),
                        ]);
                    }
                }
            }
        }

        // Create a delivery batch for partial delivery if any quantity was sent
        $sentItems = collect($itemsData)->filter(fn($qty) => (int)$qty > 0);
        if ($sentItems->isNotEmpty()) {
            $batch = DeliveryBatch::create([
                'order_id' => $order->id,
                'batch_number' => $order->batches()->count() + 1,
            ]);

            foreach ($sentItems as $itemId => $qty) {
                DeliveryBatchItem::create([
                    'delivery_batch_id' => $batch->id,
                    'order_item_id' => $itemId,
                    'quantity_sent' => (int)$qty,
                ]);
            }
        }

        // Refresh order to get updated item statuses
        $order->load('items');
        
        $allDelivered = $order->items->every(fn($item) => $item->status_item === 'delivered');

        if ($allDelivered) {
            $order->status = 'completed';
            if ($order->delivery_options === null) {
                $order->delivery_options = 'full';
            }
        } else {
            $order->status = 'not_completed';
            if ($order->delivery_options === null) {
                $order->delivery_options = 'partial';
            }
        }
        $order->save();

        return redirect()->route('delivery-orders.index')->with(['title' => 'Berhasil', 'text' => 'Partial delivery berhasil diproses.']);
    }

    public function pdf($id)
    {
        $orders = Order::with('items.barang')->findOrFail($id);
        return view('admin.pdf.delivery-order-pdf', compact('orders'));
    }

    public function getItems($id)
    {
        $order = Order::with('items.barang')->findOrFail($id);
        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'kode_barang' => $item->barang->kode_barang ?? ($item->kode_barang ?? '-'),
                'nama_barang' => $item->barang->nama_barang ?? ($item->nama_barang ?? '-'),
                'qty_pesanan' => $item->quantity,
                'qty_terkirim' => $item->delivered_quantity,
                'stok_gudang' => ($item->barang && $item->barang->status_barang === 'masuk') ? $item->barang->stok : 0,
                'satuan' => $item->barang->satuan ?? ($item->satuan ?? '-'),
            ];
        });

        return response()->json($items);
    }

    public function getHistory($id)
    {
        $order = Order::with(['batches.items.orderItem.barang'])->findOrFail($id);
        
        $history = $order->batches->map(function ($batch) {
            return [
                'id' => $batch->id,
                'batch_number' => $batch->batch_number,
                'created_at' => $batch->created_at->format('Y-m-d H:i'),
                'items' => $batch->items->map(function ($item) {
                    return [
                        'nama_barang' => $item->orderItem->barang->nama_barang ?? ($item->orderItem->nama_barang ?? '-'),
                        'quantity_sent' => $item->quantity_sent,
                    ];
                }),
            ];
        });

        return response()->json($history);
    }

    public function printBatch($batchId)
    {
        $batch = DeliveryBatch::with(['order.customer', 'order.requestOrder', 'items.orderItem.barang'])->findOrFail($batchId);
        $order = $batch->order;
        
        // We reuse the variable name 'orders' to maintain compatibility with the template if possible, 
        // but we'll adapt the template or create a new one.
        // Actually, let's pass the batch specifically.
        return view('admin.pdf.delivery-batch-pdf', compact('batch', 'order'));
    }
}
