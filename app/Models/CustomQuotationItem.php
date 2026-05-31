<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomQuotationItem extends Model
{
    protected $table = 'custom_quotation_items';

    protected $fillable = [
        'custom_quotation_id',
        'product_name',
        'qty',
        'unit',
        'price',
        'subtotal',
        'discount',
        'description',
        'images',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'integer',
        'images' => 'array',
    ];

    public function customQuotation()
    {
        return $this->belongsTo(CustomQuotation::class, 'custom_quotation_id');
    }

    // Compatibility accessors for old column names
    public function getCustomPenawaranIdAttribute()
    {
        return $this->custom_quotation_id;
    }

    public function setCustomPenawaranIdAttribute($value)
    {
        $this->attributes['custom_quotation_id'] = $value;
    }

    public function getNamaBarangAttribute()
    {
        return $this->product_name;
    }

    public function setNamaBarangAttribute($value)
    {
        $this->attributes['product_name'] = $value;
    }

    public function getSatuanAttribute()
    {
        return $this->unit;
    }

    public function setSatuanAttribute($value)
    {
        $this->attributes['unit'] = $value;
    }

    public function getHargaAttribute()
    {
        return $this->price;
    }

    public function setHargaAttribute($value)
    {
        $this->attributes['price'] = $value;
    }

    public function getDiskonAttribute()
    {
        return $this->discount;
    }

    public function setDiskonAttribute($value)
    {
        $this->attributes['discount'] = $value;
    }

    public function getKeteranganAttribute()
    {
        return $this->description;
    }

    public function setKeteranganAttribute($value)
    {
        $this->attributes['description'] = $value;
    }
}
