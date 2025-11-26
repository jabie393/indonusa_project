<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'email', 'position'];

    public function customers()
    {
        return $this->morphedByMany(Customer::class, 'pic', 'customer_pics', 'pic_id', 'customer_id')
                    ->withPivot('pic_type');
    }
}
