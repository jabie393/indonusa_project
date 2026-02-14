<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SalesOrder extends Model
{
    protected $fillable = [
        'sales_id',
        'custom_penawaran_id',
        'so_number',
        'to',
        'up',
        'subject',
        'email',
        'our_ref',
        'date',
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
        return $this->hasMany(SalesOrderItem::class);
    }

    public function customPenawaran()
    {
        return $this->belongsTo(CustomPenawaran::class, 'custom_penawaran_id');
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

    public function calculateTotals()
    {
        $subtotal = $this->items()->sum('subtotal');
        $tax = $this->tax ?? 0;
        $this->subtotal = $subtotal;
        $this->grand_total = $subtotal + $tax;
        $this->save();
    }
}
