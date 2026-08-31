<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockAllocationService
{
    /**
     * Get available stock for a specific goods ID.
     * Available Stock = Physical Stock - Allocated Stock (for active non-completed non-canceled orders).
     */
    public static function getAvailableStock(int $goodsId): int
    {
        $goods = Goods::find($goodsId);
        if (!$goods) {
            return 0;
        }

        $physicalStock = (int) $goods->stock;

        $allocated = OrderItem::where('goods_id', $goodsId)
            ->whereHas('order', function ($query) {
                $query->whereIn('status', ['open', 'under_procurement', 'sent_to_warehouse', 'approved_warehouse', 'not_completed']);
            })
            ->sum('allocated_quantity');

        return max(0, $physicalStock - (int) $allocated);
    }

    /**
     * Check if a Quotation (Listing) has shortage based on currently available stock.
     */
    public static function hasShortageForQuotation(\App\Models\Quotation $quotation): bool
    {
        $quotation->loadMissing('items');
        foreach ($quotation->items as $item) {
            if (!$item->goods_id) {
                continue;
            }
            $available = self::getAvailableStock($item->goods_id);
            if ($item->quantity > $available) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if all items in the order are fully fulfilled.
     */
    public static function isOrderFullyFulfilled(Order $order): bool
    {
        $order->loadMissing('items');
        foreach ($order->items as $item) {
            if ($item->goods_id) {
                if (($item->allocated_quantity + $item->delivered_quantity) < $item->quantity) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Allocate available stock to a Sales Order.
     * Called when a Sales Order is activated (becomes 'open').
     */
    public static function allocateAvailableStock(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->loadMissing('items.barang');

            foreach ($order->items as $item) {
                if (!$item->goods_id) {
                    continue;
                }

                // Lock the goods row to prevent race conditions
                Goods::where('id', $item->goods_id)->lockForUpdate()->first();

                $available = self::getAvailableStock($item->goods_id);
                $needed = $item->quantity - $item->delivered_quantity - $item->allocated_quantity;
                $allocate = min($available, max(0, $needed));

                if ($allocate > 0) {
                    $item->allocated_quantity += $allocate;
                }
                $item->shortage_quantity = max(0, $item->quantity - $item->delivered_quantity - $item->allocated_quantity);
                $item->save();

                self::syncProcurementOrderItemPivot($item);
            }

            // Check if fully fulfilled
            if (self::isOrderFullyFulfilled($order)) {
                $order->status = 'sent_to_warehouse';
                $order->save();
            }
        });
    }

    /**
     * Run FIFO Stock Allocation for a specific goods ID.
     * Called when physical stock increases or when allocations are released.
     */
    public static function allocateFifo(int $goodsId): void
    {
        DB::transaction(function () use ($goodsId) {
            // Lock the goods row to prevent race conditions
            $goods = Goods::where('id', $goodsId)->lockForUpdate()->first();
            if (!$goods) {
                return;
            }

            // Retrieve active order items needing this goods, sorted by queue timestamp (FIFO)
            $items = OrderItem::where('goods_id', $goodsId)
                ->whereRaw('quantity > (allocated_quantity + delivered_quantity)')
                ->whereHas('order', function ($query) {
                    $query->whereIn('status', ['open', 'under_procurement', 'not_completed']);
                })
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->orderBy('orders.queue_at', 'asc')
                ->orderBy('orders.id', 'asc')
                ->select('order_items.*')
                ->get();

            foreach ($items as $item) {
                $available = self::getAvailableStock($goodsId);
                if ($available <= 0) {
                    break;
                }

                $needed = $item->quantity - $item->delivered_quantity - $item->allocated_quantity;
                $allocate = min($available, max(0, $needed));

                if ($allocate > 0) {
                    $item->allocated_quantity += $allocate;
                    $item->shortage_quantity = max(0, $item->quantity - $item->delivered_quantity - $item->allocated_quantity);
                    $item->save();

                    self::syncProcurementOrderItemPivot($item);

                    // Check if the order is now fully fulfilled
                    $order = $item->order;
                    if (self::isOrderFullyFulfilled($order)) {
                        // Only transition to sent_to_warehouse if order hasn't been partially delivered yet
                        if ($order->items->sum('delivered_quantity') == 0 && $order->batches()->count() == 0) {
                            $order->status = 'sent_to_warehouse';
                        } else {
                            $order->status = 'not_completed';
                        }
                        $order->save();

                        // Automatically resolve pending procurements for this order as it got fulfilled by FIFO reallocation
                        $pendingProcurements = \App\Models\ProcurementOfGoods::where('order_id', $order->id)
                            ->where('status', 'pending')
                            ->get();
                        foreach ($pendingProcurements as $proc) {
                            $proc->update([
                                'status' => 'completed',
                                'notes' => ($proc->notes ? $proc->notes . ' | ' : '') . 'Terpenuhi otomatis dari realokasi stok FIFO.'
                            ]);
                            $proc->items()->update(['status' => 'completed']);
                        }
                    }
                }
            }
        });
    }

    /**
     * Synchronize allocated_quantity in procurement_order_items pivot table.
     */
    private static function syncProcurementOrderItemPivot(OrderItem $item): void
    {
        $pois = DB::table('procurement_order_items')
            ->where('order_item_id', $item->id)
            ->get();

        foreach ($pois as $poi) {
            $fulfilledShortage = max(0, $poi->quantity - $item->shortage_quantity);
            DB::table('procurement_order_items')
                ->where('id', $poi->id)
                ->update([
                    'allocated_quantity' => min($poi->quantity, $fulfilledShortage),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Release all stock allocations reserved for a Sales Order.
     * Called when a Sales Order is canceled or rejected.
     */
    public static function releaseAllocation(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->loadMissing('items');

            $goodsToAllocate = [];

            foreach ($order->items as $item) {
                if ($item->goods_id && $item->allocated_quantity > 0) {
                    $goodsId = $item->goods_id;
                    $item->allocated_quantity = 0;
                    $item->shortage_quantity = 0;
                    $item->save();

                    $goodsToAllocate[$goodsId] = true;
                }
            }

            // Trigger FIFO allocation for each affected goods
            foreach (array_keys($goodsToAllocate) as $goodsId) {
                self::allocateFifo($goodsId);
            }
        });
    }
}
