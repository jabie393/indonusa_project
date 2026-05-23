<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BarangHistory extends Model
{
    protected $table = 'goods_histories';

    protected $fillable = [
        'goods_id',
        'goods_code',
        'goods_name',
        'category',
        'stock',
        'unit',
        'location',
        'buy_price',
        'selling_price',
        'old_status',
        'new_status',
        'changed_by',
        'note',
        'form',
        'changed_at',
    ];

    protected $guarded = [];
    protected $dates = ['changed_at', 'created_at', 'updated_at'];

    // pastikan changed_at di-cast jadi datetime sehingga ->format() tersedia
    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'goods_id');
    }

    // Relasi ke user yang mengubah
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // Relasi ke user yang meng-submit (kolom `form`)
    public function formUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'form');
    }
}
