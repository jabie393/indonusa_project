<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'barang_id',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
