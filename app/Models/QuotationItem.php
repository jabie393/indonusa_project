<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $table = 'quotation_items';

    protected $fillable = [
        'quotation_id',
        'goods_id',
        'custom_product_name',
        'custom_product_description',
        'custom_product_unit',
        'product_category',
        'quantity',
        'price',
        'subtotal',
        'discount_percent',
        'ppn_percent',
        'notes',
        'images',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount_percent' => 'float',
        'ppn_percent' => 'decimal:2',
        'product_category' => 'string',
        'images' => 'array',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function barang()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    // Alias relationship to stay compatible with views checking ->product relation
    public function product()
    {
        return $this->belongsTo(Goods::class, 'goods_id');
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
        return $this->goods_id;
    }

    public function setBarangIdAttribute($value)
    {
        $this->attributes['goods_id'] = $value;
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
