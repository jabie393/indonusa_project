<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangHistory;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class Barang extends Model
{
    use HasFactory;

    // LIST KATEGORI
    public const KATEGORI = [
        'HANDTOOLS',
        'ADHESIVE AND SEALANT',
        'AUTOMOTIVE EQUIPMENT',
        'CLEANING',
        'COMPRESSOR',
        'CONSTRUCTION',
        'CUTTING TOOLS',
        'LIGHTING',
        'FASTENING',
        'GENERATOR',
        'HEALTH CARE EQUIPMENT',
        'HOSPITALITY',
        'HYDRAULIC TOOLS',
        'MARKING MACHINE',
        'MATERIAL HANDLING EQUIPMENT',
        'MEASURING AND TESTING EQUIPMENT',
        'METAL CUTTING MACHINERY',
        'PACKAGING',
        'PAINTING AND COATING',
        'PNEUMATIC TOOLS',
        'POWER TOOLS',
        'SAFETY AND PROTECTION EQUIPMENT',
        'SECURITY',
        'SHEET METAL MACHINERY',
        'STORAGE SYSTEM',
        'WELDING EQUIPMENT',
        'WOODWORKING EQUIPMENT',
        'MISCELLANEOUS',
        'OTHER CATEGORIES',
    ];

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
        'form',
    ];

    protected static function booted()
    {
        static::updated(function ($barang) {
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
                    'form'        => $barang->form,
                    'changed_by'  => Auth::id(),
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
                'changed_by'  => Auth::id(),
                'note'        => $barang->catatan ?? null,
            ]);
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'barang_id');
    }

    public function histories()
    {
        return $this->hasMany(BarangHistory::class);
    }

    // Accessor for selling price (harga jual = harga + 30%)
    public function getHargaJualAttribute()
    {
        $base = (float) ($this->attributes['harga'] ?? 0);
        return round($base * 1.3, 2);
    }
}
