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
