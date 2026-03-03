<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryBatchItem extends Model
{
    protected $fillable = ['delivery_batch_id', 'order_item_id', 'quantity_sent'];

    public function batch()
    {
        return $this->belongsTo(DeliveryBatch::class, 'delivery_batch_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
