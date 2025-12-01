<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestOrderItem extends Model
{
    protected $fillable = [
        'request_order_id',
        'barang_id',
        'quantity',
        'harga',
        'subtotal',
        'diskon_percent',
        'item_images',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'diskon_percent' => 'decimal:2',
        'item_images' => 'array',
    ];

    public function requestOrder()
    {
        return $this->belongsTo(RequestOrder::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
