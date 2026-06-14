<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'sales_id',
        'supervisor_id',
        'warehouse_id',
        'quotation_id',
        'custom_quotation_id',
        'status',
        'reason',
        'customer_name',
        'customer_id',
        'required_date',
        'customer_notes',
        'delivery_options',
        'do_number',
    ];
    protected static function boot()
    {
        parent::boot();
    }

    protected static function booted()
    {
        static::updated(function ($order) {
            if ($order->isDirty('status') && $order->quotation_id) {
                if (in_array($order->status, ['open', 'rejected_supervisor'])) {
                    $quotation = $order->quotation;
                    if ($quotation) {
                        \App\Models\QuotationHistory::create([
                            'quotation_id' => $quotation->id,
                            'quotation_type' => 'request_order',
                            'quotation_number' => $quotation->quotation_number,
                            'customer_name' => $quotation->customer_name,
                            'pic_name' => $quotation->customer?->pics?->first()?->name ?? $quotation->pic?->name ?? '-',
                            'sales_id' => $quotation->sales_id,
                            'sales_name' => $quotation->sales?->name ?? '-',
                            'grand_total' => $quotation->grand_total,
                            'status' => $order->status,
                            'reason' => $order->reason ?? '-',
                            'changed_by' => \Illuminate\Support\Facades\Auth::id() ?? $order->supervisor_id,
                            'changed_at' => now(),
                        ]);
                    }
                }
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(User::class, 'warehouse_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function requestOrder()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function customQuotation()
    {
        return $this->belongsTo(CustomQuotation::class, 'custom_quotation_id');
    }

    // Add relationship to order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function batches()
    {
        return $this->hasMany(DeliveryBatch::class, 'order_id');
    }
}
