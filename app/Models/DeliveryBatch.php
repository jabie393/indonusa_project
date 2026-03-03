<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryBatch extends Model
{
    protected $fillable = ['order_id', 'batch_number'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryBatchItem::class, 'delivery_batch_id');
    }
}
