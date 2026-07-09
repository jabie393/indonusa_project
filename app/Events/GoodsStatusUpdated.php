<?php

namespace App\Events;

use App\Models\Goods;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoodsStatusUpdated implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $barangId;
    public $tipeRequest;
    public $barangCount;

    /**
     * Create a new event instance.
     */
    public function __construct(Goods $barang)
    {
        $this->barangId = $barang->id;
        $this->tipeRequest = $barang->request_type;

        // Count pending items
        $this->barangCount = Goods::where('goods_status', 'pending')->count();
    }

    /**
     * Channel broadcasting.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('goods'),
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
