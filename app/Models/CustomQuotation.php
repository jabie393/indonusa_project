<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomQuotation extends Model
{
    protected $table = 'custom_quotations';

    public function quotation() {
        return $this->hasOne(\App\Models\Quotation::class, 'custom_quotation_id');
    }

    public function procurementOfGoods() {
        return $this->hasMany(\App\Models\ProcurementOfGoods::class, 'custom_quotation_id');
    }

    protected $fillable = [
        'sales_id',
        'quotation_number',
        'to',
        'up',
        'subject',
        'email',
        'our_ref',
        'date',
        'expired_at',
        'intro_text',
        'subtotal',
        'tax',
        'grand_total',
        'status',
        'reason',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'expired_at' => 'datetime',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function items()
    {
        return $this->hasMany(CustomQuotationItem::class, 'custom_quotation_id');
    }

    public function order()
    {
        return $this->hasOne(\App\Models\Order::class, 'custom_quotation_id');
    }

    public static function generateQuotationNumber()
    {
        $date = now()->format('Ymd');
        
        // Find max number from custom_quotations table
        $maxInTable = self::where('quotation_number', 'like', "PN-{$date}-%")
            ->selectRaw("CAST(SUBSTRING_INDEX(quotation_number, '-', -1) AS UNSIGNED) as num")
            ->orderBy('num', 'desc')
            ->first();
            
        // Find max number from quotation_histories table
        $maxInHistory = \App\Models\QuotationHistory::where('quotation_type', 'custom_quotation')
            ->where('quotation_number', 'like', "PN-{$date}-%")
            ->selectRaw("CAST(SUBSTRING_INDEX(quotation_number, '-', -1) AS UNSIGNED) as num")
            ->orderBy('num', 'desc')
            ->first();
            
        $tableNum = $maxInTable ? $maxInTable->num : 0;
        $historyNum = $maxInHistory ? $maxInHistory->num : 0;
        
        $nextNum = max($tableNum, $historyNum) + 1;
        
        return 'PN-' . $date . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public static function generateUniqueRef()
    {
        return 'REF-' . strtoupper(Str::random(8));
    }

    public function calculateTotals()
    {
        $subtotal = $this->items()->sum('subtotal');
        $tax = $this->tax ?? 0;
        $this->subtotal = $subtotal;
        $this->grand_total = $subtotal + $tax;
        $this->save();
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

        // If expiry has passed and the penawaran is still open, mark expired
        if (now() > $this->expired_at && in_array($this->status, ['open', 'sent'])) {
            $this->update(['status' => 'expired']);
        }
    }

    protected static function booted()
    {
        static::updated(function ($customQuotation) {
            if ($customQuotation->isDirty('status') && in_array($customQuotation->status, ['approved_supervisor', 'rejected_supervisor'])) {
                \App\Models\QuotationHistory::create([
                    'custom_quotation_id' => $customQuotation->id,
                    'quotation_type' => 'custom_quotation',
                    'quotation_number' => $customQuotation->quotation_number,
                    'customer_name' => $customQuotation->to,
                    'pic_name' => $customQuotation->up ?? '-',
                    'sales_id' => $customQuotation->sales_id,
                    'sales_name' => $customQuotation->sales?->name ?? '-',
                    'grand_total' => $customQuotation->grand_total,
                    'status' => $customQuotation->status,
                    'reason' => $customQuotation->reason ?? '-',
                    'changed_by' => \Illuminate\Support\Facades\Auth::id() ?? $customQuotation->approved_by,
                    'changed_at' => now(),
                ]);
            }
        });

        static::deleted(function ($customQuotation) {
            \App\Models\QuotationHistory::create([
                'custom_quotation_id' => null,
                'quotation_type' => 'custom_quotation',
                'quotation_number' => $customQuotation->quotation_number,
                'customer_name' => $customQuotation->to,
                'pic_name' => $customQuotation->up ?? '-',
                'sales_id' => $customQuotation->sales_id,
                'sales_name' => $customQuotation->sales?->name ?? '-',
                'grand_total' => $customQuotation->grand_total,
                'status' => 'deleted',
                'reason' => 'Deleted from system',
                'changed_by' => \Illuminate\Support\Facades\Auth::id(),
                'changed_at' => now(),
            ]);
        });
    }
}
