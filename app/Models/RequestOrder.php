<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RequestOrderItem;
use App\Models\SalesOrder;

class RequestOrder extends Model
{
    protected $fillable = [
        'request_number',
        'nomor_penawaran',
        'sales_id',
        'customer_name',
        'customer_id',
        'status', // pending, approved, rejected, converted, expired
        'reason', 
        'tanggal_kebutuhan',
        'tanggal_berlaku',
        'expired_at',
        'catatan_customer',
        'kategori_barang',
        'supporting_images',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'tanggal_berlaku' => 'datetime',
        'expired_at' => 'datetime',
        'supporting_images' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(RequestOrderItem::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function salesOrder()
    {
        return $this->hasOne(SalesOrder::class, 'request_order_id');
    }

    /**
     * Generate nomor penawaran (e.g., PNW-20251113-001)
     */
    public static function generateNomorPenawaran()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'PNW-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if penawaran is expired
     */
    public function isExpired()
    {
        return $this->expired_at && now() > $this->expired_at;
    }

    /**
     * Auto-mark as expired if time has passed
     */
    public function checkAndUpdateExpiry()
    {
        if (!$this->expired_at) {
            return;
        }

        if (now() > $this->expired_at && $this->status === 'pending') {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Get display status with styling
     */
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'warning',
            'pending_approval' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'converted' => 'info',
            'expired' => 'secondary',
        ];

        return $statuses[$this->status] ?? 'secondary';
    }
}
