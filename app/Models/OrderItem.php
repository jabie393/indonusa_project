<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'delivered_quantity',
        'item_status',
    ];

    // Sertakan accessor nama_barang saat model di-serialize ke array/JSON
    protected $appends = ['nama_barang'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'product_id');
    }

    public function product()
    {
        return $this->belongsTo(Barang::class, 'product_id');
    }

    // Accessor untuk nama_barang — ambil dari relasi barang/product jika tersedia, kalau tidak fallback ke atribut nama_barang
    public function getNamaBarangAttribute()
    {
        if ($this->relationLoaded('product') && $this->product) {
            return $this->product->goods_name;
        }
        if ($this->relationLoaded('barang') && $this->barang) {
            return $this->barang->goods_name;
        }

        // jika tidak ada relasi, cek apakah ada kolom nama_barang tersimpan di model (mis. legacy)
        return $this->attributes['nama_barang'] ?? null;
    }

    // Compatibility accessors for old column names
    public function getBarangIdAttribute()
    {
        return $this->product_id;
    }

    public function setBarangIdAttribute($value)
    {
        $this->attributes['product_id'] = $value;
    }

    public function getHargaAttribute()
    {
        return $this->price;
    }

    public function setHargaAttribute($value)
    {
        $this->attributes['price'] = $value;
    }

    public function getStatusItemAttribute()
    {
        return $this->item_status;
    }

    public function setStatusItemAttribute($value)
    {
        $this->attributes['item_status'] = $value;
    }
}
