<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RequestOrderItem;

class RequestOrder extends Model
{
    /**
     * Cek apakah ada item dengan diskon >20%
     *
     * @return bool
     */
    public function hasDiscountOver20(): bool
    {
        return $this->items->max('diskon_percent') > 20;
    }

    protected $fillable = [
        'request_number',
        'nomor_penawaran',
        'sales_order_number',
        'no_po',
        'sales_id',
        'customer_name',
        'customer_id',
        'subject',
        'reason', 
        'tanggal_kebutuhan',
        // tambahkan field lain jika ada
    ];

    protected $casts = [
        'tanggal_kebutuhan' => 'date',
        'tanggal_berlaku' => 'datetime',
        'expired_at' => 'datetime',
        'supporting_images' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Relasi ke Order (hasOne)
     */

    /**
     * Accessor untuk status order terkait
     *
     * @return string|null
     */
    /**
     * Accessor untuk status order terkait (label user-friendly)
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        $status = $this->order?->status;
        $labels = [
            'pending' => 'Pending',
            'open' => 'Open',
            'approved_supervisor' => 'Disetujui Supervisor',
            'rejected_supervisor' => 'Ditolak Supervisor',
            'sent_to_warehouse' => 'Dikirim ke Gudang',
            'approved_warehouse' => 'Disetujui Gudang',
            'rejected_warehouse' => 'Ditolak Gudang',
            'completed' => 'Selesai',
            'not_completed' => 'Tidak Selesai',
        ];
        if (!$status) {
            return 'Belum Diproses';
        }
        return $labels[$status] ?? $status;
    }

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

    public function order()
    {
        return $this->hasOne(Order::class, 'request_order_id');
    }

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
}
