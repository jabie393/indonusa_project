<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'sales_order_number',
        'request_order_id',
        'sales_id',
        'customer_name',
        'customer_id',
        'status', // pending, in_process, shipped, completed, cancelled
        'reason', // alasan pembatalan
        'tanggal_kebutuhan',
        'catatan_customer',
        'supervisor_id',
        'warehouse_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function requestOrder()
    {
        return $this->belongsTo(RequestOrder::class);
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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
