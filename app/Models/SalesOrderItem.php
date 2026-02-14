<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    protected $fillable = [
        'sales_order_id',
        'nama_barang',
        'qty',
        'satuan',
        'harga',
        'subtotal',
        'diskon',
        'keterangan',
        'images',
        'quantity',
    ];

    protected $casts = [
        'qty' => 'integer',
        'quantity' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'diskon' => 'integer',
        'images' => 'array',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }
}
