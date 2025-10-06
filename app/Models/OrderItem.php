<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'barang_id',
        'quantity',
        'delivered_quantity',
        'status_item',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
