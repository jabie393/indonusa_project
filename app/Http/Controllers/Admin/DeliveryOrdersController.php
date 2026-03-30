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
    /**
     * Delivery Orders untuk Sales (read-only, filter by sales_id)
     */
    public function salesIndex(Request $request)
    {
        $search  = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = \App\Models\Order::with(['items.barang', 'requestOrder.sales', 'customer'])
            ->whereHas('requestOrder', function ($q) {
                $q->where('sales_id', \Illuminate\Support\Facades\Auth::id());
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number',  'like', "%$search%")
                  ->orWhere('do_number',    'like', "%$search%")
                  ->orWhere('customer_name','like', "%$search%")
                  ->orWhereHas('requestOrder', function ($q2) use ($search) {
                      $q2->where('no_po',               'like', "%$search%")
                         ->orWhere('sales_order_number', 'like', "%$search%");
                  });
            });
        }

        $orders = $query->latest()->paginate($perPage)->appends($request->query());

        return view('admin.sales.delivery-orders.index', compact('orders'));
    }
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
            $cancelOption = $request->input('cancel_option');
            $returnItems = $request->input('return_items', []);

            if ($cancelOption === 'cancel_return') {
                foreach ($order->items as $item) {
                    if (isset($returnItems[$item->id]) && $item->delivered_quantity > 0) {
                        $returnQty = (int) $returnItems[$item->id];
                        
                        if ($returnQty > $item->delivered_quantity) {
                            $returnQty = $item->delivered_quantity; 
                        }

                        if ($returnQty > 0) {
                            $item->delivered_quantity -= $returnQty;
                            
                            if ($item->barang) {
                                $barang = $item->barang;
                                $barang->stok += $returnQty;
                                $barang->save();

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
                                    'note'        => 'Stock bertambah (' . $returnQty . ') dari pengembalian DO: ' . ($order->do_number ?? $order->order_number),
                                ]);
                            }
                        }
                    }

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

                $message = 'Order telah dibatalkan sisanya, stok terkirim telah dikembalikan, dan DO ditandai Selesai.';
            } else {
                // Case: cancel_rest (default)
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
            }
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

        // Guard: jika tidak ada qty yang diisi, batalkan proses
        $hasAnyQty = collect($itemsData)->filter(fn($qty) => (int)$qty > 0)->isNotEmpty();
        if (!$hasAnyQty) {
            return redirect()->route('delivery-orders.index')
                ->with(['title' => 'Perhatian', 'text' => 'Tidak ada item yang diisi untuk dikirim. Silakan isi jumlah barang yang akan dikirim.']);
        }

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
        $order = Order::with(['items.barang', 'requestOrder.items.barang'])->findOrFail($id);

        // Jika order_items ada dan terisi, gunakan itu
        if ($order->items->count() > 0) {
            $items = $order->items->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'kode_barang'  => $item->barang->kode_barang ?? '-',
                    'nama_barang'  => $item->barang->nama_barang ?? '-',
                    'qty_pesanan'  => $item->quantity,
                    'quantity'     => $item->quantity, // for compatibility
                    'qty_terkirim' => $item->delivered_quantity ?? 0,
                    'delivered_quantity' => $item->delivered_quantity ?? 0, // for compatibility
                    'stok_gudang'  => $item->barang ? ($item->barang->stok ?? 0) : 0,
                    'satuan'       => $item->barang->satuan ?? '-',
                    'status'       => $item->status_item ?? 'pending',
                ];
            });

            return response()->json($items);
        }

        // Fallback: order_items kosong (order lama), ambil dari request_order_items
        // sekaligus buat order_items agar ke depannya tidak perlu fallback lagi
        if ($order->requestOrder && $order->requestOrder->items->count() > 0) {
            $result = collect();

            foreach ($order->requestOrder->items as $reqItem) {
                $existing = \App\Models\OrderItem::where('order_id', $order->id)
                    ->where('barang_id', $reqItem->barang_id)
                    ->first();

                if (!$existing) {
                    $existing = \App\Models\OrderItem::create([
                        'order_id'           => $order->id,
                        'barang_id'          => $reqItem->barang_id,
                        'quantity'           => $reqItem->quantity,
                        'delivered_quantity' => 0,
                        'status_item'        => 'pending',
                        'harga'              => $reqItem->harga,
                        'subtotal'           => $reqItem->subtotal,
                    ]);
                    $existing->load('barang');
                }

                $result->push([
                    'id'           => $existing->id,
                    'kode_barang'  => $existing->barang->kode_barang ?? '-',
                    'nama_barang'  => $existing->barang->nama_barang ?? '-',
                    'qty_pesanan'  => $existing->quantity,
                    'quantity'     => $existing->quantity, // for compatibility
                    'qty_terkirim' => $existing->delivered_quantity ?? 0,
                    'delivered_quantity' => $existing->delivered_quantity ?? 0, // for compatibility
                    'stok_gudang'  => $existing->barang ? ($existing->barang->stok ?? 0) : 0,
                    'satuan'       => $existing->barang->satuan ?? '-',
                    'status'       => $existing->status_item ?? 'pending',
                ]);
            }

            return response()->json($result);
        }

        return response()->json([]);
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
