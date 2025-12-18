<?php

namespace App\Events;

use App\Models\Barang;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BarangStatusUpdated implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $barangId;
    public $tipeRequest;
    public $barangCount;

    /**
     * Create a new event instance.
     */
    public function __construct(Barang $barang)
    {
        $this->barangId = $barang->id;
        $this->tipeRequest = $barang->tipe_request;

        // Count pending items
        $this->barangCount = Barang::where('status_barang', 'ditinjau')->count();
    }

    /**
     * Channel broadcasting.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('barangs'),
        ];
    }

    /**
     * Data broadcast ke frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'barangId'    => $this->barangId,
            'tipeRequest' => $this->tipeRequest,
            'barangCount' => $this->barangCount,
        ];
    }
}
