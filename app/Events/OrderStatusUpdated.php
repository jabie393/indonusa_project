<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderId;
    public $orderCount;

    /**
     * Create a new event instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;

        // Query ringan, aman dilakukan di event
        $this->orderCount = Order::where('status', 'sent_to_warehouse')->count();
    }

    /**
     * Channel broadcasting.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('orders'),
        ];
    }

    /**
     * Data broadcast ke frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'orderId'    => $this->orderId,
            'orderCount' => $this->orderCount,
        ];
    }
}
