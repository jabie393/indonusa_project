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
        'status', // pending, pending_approval, approved, rejected, open, expired
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
        'tanggal_kebutuhan' => 'date',
        'tanggal_berlaku' => 'datetime',
        'expired_at' => 'datetime',
        'supporting_images' => 'array',
    ];

    /**
     * Accessor untuk tanggal_kebutuhan yang aman
     */
    public function getTanggalKebutuhanFormattedAttribute()
    {
        if (!$this->tanggal_kebutuhan) {
            return '-';
        }
        if (is_string($this->tanggal_kebutuhan)) {
            return \Carbon\Carbon::parse($this->tanggal_kebutuhan)->format('d M Y');
        }
        return $this->tanggal_kebutuhan->format('d M Y');
    }

    /**
     * Accessor untuk tanggal_berlaku yang aman
     */
    public function getTanggalBerlakuFormattedAttribute()
    {
        if (!$this->tanggal_berlaku) {
            return '-';
        }
        if (is_string($this->tanggal_berlaku)) {
            return \Carbon\Carbon::parse($this->tanggal_berlaku)->format('d M Y');
        }
        return $this->tanggal_berlaku->format('d M Y');
    }

    /**
     * Accessor untuk expired_at yang aman
     */
    public function getExpiredAtFormattedAttribute()
    {
        if (!$this->expired_at) {
            return '-';
        }
        if (is_string($this->expired_at)) {
            return \Carbon\Carbon::parse($this->expired_at)->format('d M Y');
        }
        return $this->expired_at->format('d M Y');
    }

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

    public function order()
    {
        return $this->hasOne(Order::class, 'request_order_id');
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

        // If expiry has passed and the request is still editable/pending (open or pending), mark expired
        if (now() > $this->expired_at && in_array($this->status, ['pending', 'open'])) {
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
            'open' => 'primary',
            'expired' => 'secondary',
        ];

        return $statuses[$this->status] ?? 'secondary';
    }
}
