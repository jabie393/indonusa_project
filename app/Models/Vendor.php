<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'vendor_code',
        'vendor_name',
        'company_type',
        'npwp',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'pic_name',
        'pic_phone',
        'pic_email',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'term_of_payment',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'term_of_payment' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->vendor_code)) {
                $vendor->vendor_code = self::generateVendorCode();
            }
            if (Auth::check()) {
                $vendor->created_by = Auth::id();
                $vendor->updated_by = Auth::id();
            }
        });

        static::updating(function ($vendor) {
            if (Auth::check()) {
                $vendor->updated_by = Auth::id();
            }
        });
    }

    /**
     * Generate otomatis kode vendor, e.g. VND-0001
     */
    public static function generateVendorCode(): string
    {
        $prefix = 'VND-';
        $lastVendor = self::where('vendor_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastVendor && preg_match('/' . preg_quote($prefix, '/') . '(\d+)/', $lastVendor->vendor_code, $matches)) {
            $nextNum = (int) $matches[1] + 1;
        } else {
            $nextNum = self::count() + 1;
        }

        return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * User pembuat data vendor.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User pengubah data vendor.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Pengadaan yang menggunakan vendor ini.
     */
    public function procurements(): HasMany
    {
        return $this->hasMany(ProcurementOfGoods::class, 'vendor_id');
    }
}
