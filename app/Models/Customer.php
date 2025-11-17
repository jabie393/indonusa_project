<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_customer',
        'email',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'tipe_customer', // retail, wholesale, distributor
        'created_by',
        'updated_by',
        'status', // active, inactive
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
}
