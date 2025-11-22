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
        'intro_text',
        'subtotal',
        'tax',
        'grand_total',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
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
}
