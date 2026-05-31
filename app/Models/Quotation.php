<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\QuotationItem;
use Illuminate\Support\Carbon;

class Quotation extends Model
{
    protected $table = 'quotations';

    /**
     * Generate unique Sales Order Number (NO.SO) with format:
     * SO-[YYYYMMDD]-[4 digit urut]
     *
     * @return string
     */
    public static function generateSalesOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())
            ->whereNotNull('sales_order_number')
            ->where('sales_order_number', 'like', 'SO-' . $date . '-%')
            ->count() + 1;
        $urut = str_pad($count, 4, '0', STR_PAD_LEFT);
        $noSo = 'SO-' . $date . '-' . $urut;
        // Pastikan unik di seluruh database
        while (self::where('sales_order_number', $noSo)->exists()) {
            $count++;
            $urut = str_pad($count, 4, '0', STR_PAD_LEFT);
            $noSo = 'SO-' . $date . '-' . $urut;
        }
        return $noSo;
    }

    /**
     * Cek apakah PDF bisa didownload sesuai aturan diskon dan status order
     * @return bool
     */
    public function canDownloadPdf(): bool
    {
        $maxDiskon = $this->items->max('discount_percent');
        $status = $this->order?->status;
        if ($maxDiskon === null) return false; // Tidak ada item
        if ($status === 'approved_supervisor') return true;
        if ($maxDiskon <= 20 && $status === 'open') return true;
        if ($maxDiskon > 20 && $status === 'open') return true;
        if ($status === 'sent_to_supervisor') return false;
        if ($status === 'rejected_supervisor') return false;
        return false;
    }

    protected $fillable = [
        'request_number',
        'quotation_number',
        'sales_order_number',
        'no_po',
        'sales_id',
        'customer_name',
        'customer_id',
        'subject',
        'reason',
        'required_date',
        'valid_date',
        'expired_at',
        'customer_notes',
        'subtotal',
        'tax',
        'grand_total',
        'product_category',
        'supporting_images',
        'custom_quotation_id',
    ];

    public function customQuotation() {
        return $this->belongsTo(\App\Models\CustomQuotation::class, 'custom_quotation_id');
    }

    /**
     * Cek apakah ada item dengan diskon >20%
     *
     * @return bool
     */
    public function hasDiscountOver20(): bool
    {
        return $this->items->max('discount_percent') > 20;
    }

    protected $casts = [
        'required_date' => 'date',
        'valid_date' => 'datetime',
        'expired_at' => 'datetime',
        'supporting_images' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

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
            'sent_to_supervisor' => 'Waiting for Supervisor Approval',
            'approved_supervisor' => 'Approved by Supervisor',
            'rejected_supervisor' => 'Rejected by Supervisor',
            'sent_to_warehouse' => 'Sent to Warehouse',
            'approved_warehouse' => 'Approved by Warehouse',
            'rejected_warehouse' => 'Rejected by Warehouse',
            'completed' => 'Completed',
            'not_completed' => 'Partial Delivery',
        ];
        if (!$status) {
            return 'Belum Diproses';
        }
        return $labels[$status] ?? $status;
    }

    /**
     * Accessor untuk required_date yang aman
     */
    public function getRequiredDateFormattedAttribute()
    {
        if (!$this->required_date) {
            return '-';
        }
        if (is_string($this->required_date)) {
            return \Carbon\Carbon::parse($this->required_date)->format('d M Y');
        }
        return $this->required_date->format('d M Y');
    }

    /**
     * Accessor untuk valid_date yang aman
     */
    public function getValidDateFormattedAttribute()
    {
        if (!$this->valid_date) {
            return '-';
        }
        if (is_string($this->valid_date)) {
            return \Carbon\Carbon::parse($this->valid_date)->format('d M Y');
        }
        return $this->valid_date->format('d M Y');
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
        return $this->hasMany(QuotationItem::class, 'quotation_id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'quotation_id');
    }

    public static function generateQuotationNumber()
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

    // Compatibility accessors for old column names
    public function getNomorPenawaranAttribute()
    {
        return $this->quotation_number;
    }

    public function setNomorPenawaranAttribute($value)
    {
        $this->attributes['quotation_number'] = $value;
    }

    public function getTanggalKebutuhanAttribute()
    {
        return $this->required_date;
    }

    public function setTanggalKebutuhanAttribute($value)
    {
        $this->attributes['required_date'] = $value;
    }

    public function getTanggalBerlakuAttribute()
    {
        return $this->valid_date;
    }

    public function setTanggalBerlakuAttribute($value)
    {
        $this->attributes['valid_date'] = $value;
    }

    public function getCatatanCustomerAttribute()
    {
        return $this->customer_notes;
    }

    public function setCatatanCustomerAttribute($value)
    {
        $this->attributes['customer_notes'] = $value;
    }

    public function getKategoriBarangAttribute()
    {
        return $this->product_category;
    }

    public function setKategoriBarangAttribute($value)
    {
        $this->attributes['product_category'] = $value;
    }

    public function getTanggalKebutuhanFormattedAttribute()
    {
        return $this->required_date_formatted;
    }

    public function getTanggalBerlakuFormattedAttribute()
    {
        return $this->valid_date_formatted;
    }
}
