<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    /**
     * Get the receipts for the supplier.
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }
}
