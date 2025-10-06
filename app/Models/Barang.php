<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangHistory;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipe_request',
        'status_barang',
        'status_listing',
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok',
        'satuan',
        'lokasi',
        'harga',
        'deskripsi',
        'gambar',
        'catatan',
    ];

    protected static function booted()
    {
        static::updated(function ($barang) {
            // Cek apakah status_barang berubah
            if ($barang->isDirty('status_barang')) {
                BarangHistory::create([
                    'barang_id'   => $barang->id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'kategori'    => $barang->kategori,
                    'stok'        => $barang->stok,
                    'satuan'      => $barang->satuan,
                    'lokasi'      => $barang->lokasi,
                    'harga'       => $barang->harga,
                    'old_status'  => $barang->getOriginal('status_barang'),
                    'new_status'  => $barang->status_barang,
                    'changed_by'  => auth()->id(),
                    'note'        => $barang->catatan ?? null,
                ]);
            }
        });

        static::deleted(function ($barang) {
            BarangHistory::create([
                'barang_id'   => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori'    => $barang->kategori,
                'stok'        => $barang->stok,
                'satuan'      => $barang->satuan,
                'lokasi'      => $barang->lokasi,
                'harga'       => $barang->harga,
                'old_status'  => $barang->status_barang,
                'new_status'  => 'dihapus',
                'changed_by'  => auth()->id(),
                'note'        => $barang->catatan ?? null,
            ]);
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'barang_id');
    }
    
    // Relasi ke history
    public function histories()
    {
        return $this->hasMany(BarangHistory::class);
    }
}
