<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'name', 'phone', 'email', 'position'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
