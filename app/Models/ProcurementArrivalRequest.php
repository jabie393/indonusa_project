<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcurementArrivalRequest extends Model
{
    protected $table = 'procurement_arrival_requests';

    protected $fillable = [
        'procurement_of_goods_item_id',
        'good_id',
        'received_at',
        'quantity',
        'unit_cost',
        'status',
        'reject_reason',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Get the good associated with the arrival request.
     */
    public function good(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'good_id');
    }

    /**
     * Get the associated procurement item.
     */
    public function procurementOfGoodsItem(): BelongsTo
    {
        return $this->belongsTo(ProcurementOfGoodsItem::class, 'procurement_of_goods_item_id');
    }
}
