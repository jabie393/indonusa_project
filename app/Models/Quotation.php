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
        if ($maxDiskon === null) {
            return false; // Tidak ada item
        }

        if (in_array($status, ['open', 'approved_supervisor', 'sent_to_warehouse', 'approved_warehouse', 'not_completed', 'completed'], true)) {
            return true;
        }

        if (in_array($status, ['sent_to_supervisor', 'rejected_supervisor', 'rejected_warehouse'], true)) {
            return false;
        }

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
        'custom_quotation_id',
        'pic_id',
        'pic_name',
    ];

    public function customQuotation() {
        return $this->belongsTo(\App\Models\CustomQuotation::class, 'custom_quotation_id');
    }

    public function pic()
    {
        return $this->belongsTo(\App\Models\Pic::class, 'pic_id');
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

        if ($status === 'sent_to_warehouse' && $this->custom_quotation_id) {
            $hasActiveProcurement = \App\Models\ProcurementOfGoods::where('custom_quotation_id', $this->custom_quotation_id)
                ->where('status', '!=', 'completed')
                ->exists();
            $noProcurement = !\App\Models\ProcurementOfGoods::where('custom_quotation_id', $this->custom_quotation_id)->exists();

            if ($hasActiveProcurement || $noProcurement) {
                return 'Under Procurement';
            }
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
        
        // Find max number from quotations table
        $maxInTable = self::where('quotation_number', 'like', "PNW-{$date}-%")
            ->selectRaw("CAST(SUBSTRING_INDEX(quotation_number, '-', -1) AS UNSIGNED) as num")
            ->orderBy('num', 'desc')
            ->first();
            
        // Find max number from quotation_histories table
        $maxInHistory = \App\Models\QuotationHistory::where('quotation_type', 'request_order')
            ->where('quotation_number', 'like', "PNW-{$date}-%")
            ->selectRaw("CAST(SUBSTRING_INDEX(quotation_number, '-', -1) AS UNSIGNED) as num")
            ->orderBy('num', 'desc')
            ->first();
            
        $tableNum = $maxInTable ? $maxInTable->num : 0;
        $historyNum = $maxInHistory ? $maxInHistory->num : 0;
        
        $nextNum = max($tableNum, $historyNum) + 1;
        
        return 'PNW-' . $date . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if penawaran is expired
     */
    public function isExpired()
    {
        return $this->expired_at && now() > $this->expired_at;
    }

    protected static function booted()
    {
        static::deleted(function ($quotation) {
            \App\Models\QuotationHistory::create([
                'quotation_id' => null,
                'quotation_type' => 'request_order',
                'quotation_number' => $quotation->quotation_number,
                'customer_name' => $quotation->customer_name,
                'pic_name' => $quotation->customer?->pics?->first()?->name ?? $quotation->pic?->name ?? '-',
                'sales_id' => $quotation->sales_id,
                'sales_name' => $quotation->sales?->name ?? '-',
                'grand_total' => $quotation->grand_total,
                'status' => 'deleted',
                'reason' => 'Deleted from system',
                'changed_by' => \Illuminate\Support\Facades\Auth::id(),
                'changed_at' => now(),
            ]);
        });
    }
}
