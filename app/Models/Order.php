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
        'request_order_id',
        'custom_penawaran_id',
        'status',
        'reason',
        'customer_name',
        'customer_id',
        'tanggal_kebutuhan',
        'catatan_customer',
    ];

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

    public function requestOrder()
    {
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function customPenawaran()
    {
        return $this->belongsTo(CustomPenawaran::class, 'custom_penawaran_id');
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
}
