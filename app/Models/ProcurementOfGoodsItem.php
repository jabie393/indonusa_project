<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementOfGoodsItem extends Model
{
    protected $table = 'procurement_of_goods_items';

    protected $fillable = [
        'procurement_of_goods_id',
        'goods_id',
        'qty_requested',
        'qty_ordered',
        'qty_received',
        'unit',
        'buy_price',
        'selling_price',
        'status',
    ];

    protected $casts = [
        'qty_requested' => 'integer',
        'qty_ordered' => 'integer',
        'qty_received' => 'integer',
        'buy_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function procurementOfGoods()
    {
        return $this->belongsTo(ProcurementOfGoods::class, 'procurement_of_goods_id');
    }

    public function goods()
    {
        return $this->belongsTo(Barang::class, 'goods_id');
    }

    public function procurementArrivalRequests()
    {
        return $this->hasMany(ProcurementArrivalRequest::class, 'procurement_of_goods_item_id');
    }
}
