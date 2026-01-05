<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomPenawaran extends Model
{
    protected $fillable = [
        'sales_id',
        'penawaran_number',
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
        return $this->hasMany(CustomPenawaranItem::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'custom_penawaran_id');
    }

    public static function generatePenawaranNumber()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'PN-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
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
}
