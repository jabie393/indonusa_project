<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangHistory extends Model
{
    protected $table = 'barang_histories';

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
        'changed_at',
    ];

    // Relasi ke user yang mengubah
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
