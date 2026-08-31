<?php

namespace App\Events;

use App\Models\Goods;
use App\Models\CustomQuotation;
use App\Models\Order;
use App\Models\ProcurementArrivalRequest;
use App\Models\ProcurementOfGoods;
use App\Models\Quotation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeNotification implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $recipientRole;
    public $salesId;
    public $type;
    public $title;
    public $message;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($recipientRole, $salesId, $type, $title = null, $message = null)
    {
        $this->recipientRole = $recipientRole;
        $this->salesId = $salesId;
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;

        // Hitung semua count badge yang relevan secara real-time
        $this->data = [
            'pendingSentQuotation' => Quotation::whereHas('order', function ($query) {
                $query->where('status', 'sent_to_supervisor');
            })->count(),
            
            'pendingCustomQuotation' => CustomQuotation::where('status', 'pending_approval')->count(),
            
            'goodsInProcurementPendingCount' => CustomQuotation::where('status', 'sent_to_quotation')
                ->whereHas('order', function ($query) {
                    $query->where('status', 'under_procurement');
                })
                ->doesntHave('procurementOfGoods')
                ->count(),
                
            'goodsInProcurementRevisionCount' => ProcurementOfGoods::whereNotIn('status', ['completed', 'canceled'])->count(),

            'supplyOrderCount' => Goods::where('goods_status', 'pending')
                ->where('status_listing', '!=', 'non_listing')
                ->whereDoesntHave('procurementOfGoodsItems')
                ->count(),
                
            'procOrderCount' => ProcurementArrivalRequest::where('status', 'pending')->count(),
            
            'deliveryOrderCount' => Order::where(function ($q) {
                $q->whereIn('status', ['sent_to_warehouse', 'not_completed'])
                  ->orWhere(function ($sub) {
                      $sub->where('status', 'under_procurement')
                          ->whereHas('items', function ($iq) {
                              $iq->where('allocated_quantity', '>', 0);
                          });
                  });
            })->count(),
        ];

        // Jika salesId disertakan, ambil jumlah spesifik untuk sales tersebut
        if ($salesId) {
            $this->data['rejectedQuotationCount'] = Quotation::where('sales_id', $salesId)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'rejected_supervisor');
                })->count();
                
            $this->data['rejectedCustomQuotationCount'] = CustomQuotation::where('sales_id', $salesId)
                ->where('status', 'rejected_supervisor')->count();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('realtime-notifications'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'recipientRole' => $this->recipientRole,
            'salesId'       => $this->salesId,
            'type'          => $this->type,
            'title'         => $this->title,
            'message'       => $this->message,
            'data'          => $this->data,
        ];
    }
}
