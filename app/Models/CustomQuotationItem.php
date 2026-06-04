<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomQuotationItem extends Model
{
    protected $table = 'custom_quotation_items';

    protected $fillable = [
        'custom_quotation_id',
        'goods_id',
        'category',
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

    public function goods()
    {
        return $this->belongsTo(Barang::class, 'goods_id');
    }
}


