<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $fillable = [
        'sales_order_id',
        'request_order_item_id',
        'barang_id',
        'quantity',
        'delivered_quantity',
        'harga',
        'subtotal',
        'status_item', // pending, partial, completed
    ];

    protected $casts = [
        'quantity' => 'integer',
        'delivered_quantity' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function requestOrderItem()
    {
        return $this->belongsTo(RequestOrderItem::class);
    }
}
