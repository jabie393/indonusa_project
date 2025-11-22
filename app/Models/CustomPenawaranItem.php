<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPenawaranItem extends Model
{
    protected $fillable = [
        'custom_penawaran_id',
        'nama_barang',
        'qty',
        'satuan',
        'harga',
        'subtotal',
        'images',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'images' => 'array',
    ];

    public function customPenawaran()
    {
        return $this->belongsTo(CustomPenawaran::class);
    }
}
