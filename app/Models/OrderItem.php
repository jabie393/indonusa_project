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

    // Sertakan accessor nama_barang saat model di-serialize ke array/JSON
    protected $appends = ['nama_barang'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Accessor untuk nama_barang — ambil dari relasi barang jika tersedia, kalau tidak fallback ke atribut nama_barang
    public function getNamaBarangAttribute()
    {
        if ($this->relationLoaded('barang') && $this->barang) {
            return $this->barang->nama_barang;
        }

        // jika tidak ada relasi, cek apakah ada kolom nama_barang tersimpan di model (mis. legacy)
        return $this->attributes['nama_barang'] ?? null;
    }
}
