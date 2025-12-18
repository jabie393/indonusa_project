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
        if ($barang->status_barang === 'ditinjau') {
            event(new BarangStatusUpdated($barang));
        }
    }

    /**
     * Handle the Barang "updated" event.
     */
    public function updated(Barang $barang): void
    {
        if ($barang->isDirty('status_barang') && $barang->status_barang === 'ditinjau') {
            event(new BarangStatusUpdated($barang));
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
