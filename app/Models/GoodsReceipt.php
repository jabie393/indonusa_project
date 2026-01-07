<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceipt extends Model
{
    protected $fillable = [
        'good_id',
        'supplier_id',
        'received_at',
        'approved_by',
        'quantity',
        'unit_cost',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'unit_cost' => 'decimal:2',
    ];

    /**
     * Get the good that was received.
     */
    public function good(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'good_id');
    }

    /**
     * Get the supplier (User with General Affair role) who provided the goods.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the user who approved the receipt.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
