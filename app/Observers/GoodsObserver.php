<?php

namespace App\Observers;

use App\Models\Goods;
use App\Events\GoodsStatusUpdated;

class GoodsObserver
{
    /**
     * Handle the Goods "created" event.
     */
    public function created(Goods $barang): void
    {
        if ($barang->goods_status === 'pending') {
            event(new GoodsStatusUpdated($barang));
            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'barang_pending',
                $barang->request_type === 'new_stock' ? 'Permintaan Stok!' : 'Barang Baru!',
                $barang->request_type === 'new_stock' 
                    ? "Ada permintaan stok baru yang perlu ditinjau." 
                    : "Ada barang baru yang perlu ditinjau."
            ));
        }
    }

    /**
     * Handle the Goods "updated" event.
     */
    public function updated(Goods $barang): void
    {
        if ($barang->isDirty('goods_status') && $barang->goods_status === 'pending') {
            event(new GoodsStatusUpdated($barang));
            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'barang_pending',
                $barang->request_type === 'new_stock' ? 'Permintaan Stok!' : 'Barang Baru!',
                $barang->request_type === 'new_stock' 
                    ? "Ada permintaan stok baru yang perlu ditinjau." 
                    : "Ada barang baru yang perlu ditinjau."
            ));
        }
    }

    /**
     * Handle the Goods "deleted" event.
     */
    public function deleted(Goods $barang): void
    {
        //
    }

    /**
     * Handle the Goods "restored" event.
     */
    public function restored(Goods $barang): void
    {
        //
    }

    /**
     * Handle the Goods "force deleted" event.
     */
    public function forceDeleted(Goods $barang): void
    {
        //
    }
}
