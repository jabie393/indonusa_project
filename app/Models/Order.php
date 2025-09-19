<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'sales_id',
        'pt_id',
        'warehouse_id',
        'status',
        'reason'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function pt()
    {
        return $this->belongsTo(User::class, 'pt_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(User::class, 'warehouse_id');
    }
}
