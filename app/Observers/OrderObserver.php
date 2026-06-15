<?php

namespace App\Observers;

use App\Models\Order;
use App\Events\OrderStatusUpdated;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if ($order->status === 'sent_to_warehouse') {
            event(new OrderStatusUpdated($order->id));
            $this->dispatchOrderNotification($order);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status') && $order->status === 'sent_to_warehouse') {
            event(new OrderStatusUpdated($order->id));
            $this->dispatchOrderNotification($order);
        }
    }

    /**
     * Dispatch notification based on order type (procurement vs delivery).
     */
    private function dispatchOrderNotification(Order $order): void
    {
        $customQuotation = null;
        if ($order->custom_quotation_id) {
            $customQuotation = \App\Models\CustomQuotation::find($order->custom_quotation_id);
        } elseif ($order->quotation_id) {
            $quotation = \App\Models\Quotation::find($order->quotation_id);
            if ($quotation && $quotation->custom_quotation_id) {
                $customQuotation = \App\Models\CustomQuotation::find($quotation->custom_quotation_id);
            }
        }

        if ($customQuotation && $customQuotation->status === 'sent_to_quotation') {
            event(new \App\Events\RealTimeNotification(
                'General Affair',
                null,
                'procurement_submitted',
                'Pengadaan Baru!',
                "Ada Custom Quotation baru yang membutuhkan pengadaan: {$customQuotation->quotation_number}"
            ));
        } else {
            event(new \App\Events\RealTimeNotification(
                'Warehouse',
                null,
                'delivery_order_submitted',
                'Order Baru!',
                'Ada order baru yang perlu ditinjau.'
            ));
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
