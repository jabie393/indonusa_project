<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationHistory extends Model
{
    protected $table = 'quotation_histories';

    protected $fillable = [
        'quotation_id',
        'custom_quotation_id',
        'quotation_type',
        'quotation_number',
        'customer_name',
        'pic_name',
        'sales_id',
        'sales_name',
        'grand_total',
        'status',
        'reason',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
        'grand_total' => 'decimal:2',
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function customQuotation(): BelongsTo
    {
        return $this->belongsTo(CustomQuotation::class, 'custom_quotation_id');
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
