<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalesOrder extends Model
{
    protected $fillable = [
        'sales_order_number',
        'request_order_id',
        'sales_id',
        'customer_name',
        'customer_id',
        'status',
        'reason',
        'tanggal_kebutuhan',
        'catatan_customer',
        'supervisor_id',
        'warehouse_id',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_kebutuhan' => 'date',
        'approved_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function requestOrder()
    {
        return $this->belongsTo(RequestOrder::class, 'request_order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Relasi ke penawaran (CustomPenawaran) jika diperlukan untuk pencarian
    public function penawaran()
    {
        return $this->belongsTo(\App\Models\CustomPenawaran::class, 'custom_penawaran_id');
    }

    public static function generateSONumber()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'SO-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public static function generateUniqueRef()
    {
        return 'REF-' . strtoupper(Str::random(8));
    }
}
