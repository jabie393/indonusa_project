<?php

namespace App\Observers;

use App\Models\Barang;
use App\Events\BarangStatusUpdated;

class BarangObserver
{
    /**
     * Handle the Barang "created" event.
     */
    public function created(Barang $barang): void
    {
        if ($barang->goods_status === 'pending') {
            event(new BarangStatusUpdated($barang));
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
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        if ($barang->isDirty('goods_status') && $barang->goods_status === 'pending') {
            event(new BarangStatusUpdated($barang));
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
     * Handle the Barang "deleted" event.
     */
    public function deleted(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "restored" event.
     */
    public function restored(Barang $barang): void
    {
        //
    }

    /**
     * Handle the Barang "force deleted" event.
     */
    public function forceDeleted(Barang $barang): void
    {
        //
    }
}
