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
        'spv_id',
        'spv_approved_at',
        'rejected_by',
        'rejected_by_role',
        'rejected_at',
        'reject_reason',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'spv_approved_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    /**
     * Get the supervisor who reviewed this arrival.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'spv_id');
    }

    /**
     * Get the user who rejected this arrival (SPV or Warehouse).
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
