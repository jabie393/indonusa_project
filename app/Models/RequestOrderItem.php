<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestOrderItem extends Model
{
    protected $fillable = [
        'request_order_id',
        'barang_id',
        'kategori_barang',
        'quantity',
        'harga',
        'subtotal',
        'diskon_percent',
        'ppn_percent',
        'item_images',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'diskon_percent' => 'decimal:2',
        'ppn_percent' => 'decimal:2',
        'item_images' => 'array',
        'kategori_barang' => 'string',
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
