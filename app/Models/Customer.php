<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'npwp',
        'term_of_payments',
        'credit_limit',
        'email',
        'phone',
        'billing_address',
        'shipping_address',
        'city',
        'province',
        'postal_code',
        'pic',
        'customer_type',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $appends = [
        'nama_customer',
        'kredit_limit',
        'telepon',
        'alamat_penagihan',
        'alamat_pengiriman',
        'kota',
        'provinsi',
        'kode_pos',
        'tipe_customer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'term_of_payments' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->created_by = \Illuminate\Support\Facades\Auth::id();
            $customer->updated_by = \Illuminate\Support\Facades\Auth::id(); // Tambahkan ini untuk mengisi kolom updated_by saat creating
        });

        static::updating(function ($customer) {
            $customer->updated_by = \Illuminate\Support\Facades\Auth::id();
        });
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->billing_address}, {$this->city}, {$this->province} {$this->postal_code}";
    }

    public function pics()
    {
        return $this->hasMany(Pic::class, 'customer_id');
    }


    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // Compatibility accessors for old column names
    public function getNamaCustomerAttribute()
    {
        return $this->customer_name;
    }

    public function setNamaCustomerAttribute($value)
    {
        $this->attributes['customer_name'] = $value;
    }

    public function getKreditLimitAttribute()
    {
        return $this->credit_limit;
    }

    public function setKreditLimitAttribute($value)
    {
        $this->attributes['credit_limit'] = $value;
    }

    public function getTeleponAttribute()
    {
        return $this->phone;
    }

    public function setTeleponAttribute($value)
    {
        $this->attributes['phone'] = $value;
    }

    public function getAlamatPenagihanAttribute()
    {
        return $this->billing_address;
    }

    public function setAlamatPenagihanAttribute($value)
    {
        $this->attributes['billing_address'] = $value;
    }

    public function getAlamatPengirimanAttribute()
    {
        return $this->shipping_address;
    }

    public function setAlamatPengirimanAttribute($value)
    {
        $this->attributes['shipping_address'] = $value;
    }

    public function getKotaAttribute()
    {
        return $this->city;
    }

    public function setKotaAttribute($value)
    {
        $this->attributes['city'] = $value;
    }

    public function getProvinsiAttribute()
    {
        return $this->province;
    }

    public function setProvinsiAttribute($value)
    {
        $this->attributes['province'] = $value;
    }

    public function getKodePosAttribute()
    {
        return $this->postal_code;
    }

    public function setKodePosAttribute($value)
    {
        $this->attributes['postal_code'] = $value;
    }

    public function getTipeCustomerAttribute()
    {
        return $this->customer_type;
    }

    public function setTipeCustomerAttribute($value)
    {
        $this->attributes['customer_type'] = $value;
    }
}
