<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_customer',
        'npwp',
        'term_of_payments',
        'kredit_limit',
        'email',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'pic',
        'tipe_customer',
        'created_by',
        'updated_by',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->created_by = auth()->id();
            $customer->updated_by = auth()->id(); // Tambahkan ini untuk mengisi kolom updated_by saat creating
        });

        static::updating(function ($customer) {
            $customer->updated_by = auth()->id();
        });
    }

    public function requestOrders()
    {
        return $this->hasMany(RequestOrder::class, 'customer_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
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
        return "{$this->alamat}, {$this->kota}, {$this->provinsi} {$this->kode_pos}";
    }

    public function pics()
    {
        return $this->morphToMany(Pic::class, 'pic', 'customer_pics', 'customer_id', 'pic_id')
                    ->withPivot('pic_type');
    }

    public function users()
    {
        return $this->morphToMany(User::class, 'pic', 'customer_pics', 'customer_id', 'pic_id')
                    ->withPivot('pic_type');
    }
}
