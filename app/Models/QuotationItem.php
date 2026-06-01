<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $table = 'quotation_items';

    protected $fillable = [
        'quotation_id',
        'product_id',
        'custom_product_name',
        'product_category',
        'quantity',
        'price',
        'subtotal',
        'discount_percent',
        'ppn_percent',
        'images',
        'item_images',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'ppn_percent' => 'decimal:2',
        'images' => 'array',
        'item_images' => 'array',
        'product_category' => 'string',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'product_id');
    }

    // Alias relationship to stay compatible with views checking ->product relation
    public function product()
    {
        return $this->belongsTo(Barang::class, 'product_id');
    }

    // Compatibility accessors for old column names
    public function getRequestOrderIdAttribute()
    {
        return $this->quotation_id;
    }

    public function setRequestOrderIdAttribute($value)
    {
        $this->attributes['quotation_id'] = $value;
    }

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

    public function getDiskonPercentAttribute()
    {
        return $this->discount_percent;
    }

    public function setDiskonPercentAttribute($value)
    {
        $this->attributes['discount_percent'] = $value;
    }

    public function getKategoriBarangAttribute()
    {
        return $this->product_category;
    }

    public function setKategoriBarangAttribute($value)
    {
        $this->attributes['product_category'] = $value;
    }

    public function getNamaBarangCustomAttribute()
    {
        return $this->custom_product_name;
    }

    public function setNamaBarangCustomAttribute($value)
    {
        $this->attributes['custom_product_name'] = $value;
    }
}
