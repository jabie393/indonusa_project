<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class BarangHistory extends Model
{
    protected $table = 'goods_histories';

    protected $fillable = [
        'barang_id',
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok',
        'satuan',
        'lokasi',
        'harga',
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
        return $this->belongsTo(Barang::class, 'barang_id');
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
