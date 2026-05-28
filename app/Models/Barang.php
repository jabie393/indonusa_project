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

    protected $table = 'goods';

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
        'request_type',
        'goods_status',
        'status_listing',
        'goods_code',
        'goods_name',
        'category',
        'stock',
        'unit',
        'location',
        'buy_price',
        'selling_price',
        'description',
        'image',
        'note',
        'submission_reason',
        'form',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Barang::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Barang::class, 'parent_id');
    }

    protected static function booted()
    {
        static::saving(function ($barang) {
            // Auto-markup +15% HANYA untuk barang baru tipe 'primary' jika harga jual kosong/0
            if (!$barang->exists && $barang->request_type === 'primary') {
                if (empty($barang->selling_price) || (float)$barang->selling_price === 0.0) {
                    $barang->selling_price = round($barang->buy_price * 1.15, 2);
                }
            }
        });

        static::updated(function ($barang) {
            if ($barang->isDirty('goods_status')) {
                $action = null;
                if ($barang->goods_status === 'masuk') {
                    $action = 'Barang di approve oleh admin warehouse dan berhasil masuk ke gudang';
                } elseif ($barang->goods_status === 'ditolak') {
                    $action = 'Barang ditolak oleh admin warehouse sesuai catatan penolakan';
                }

                BarangHistory::create([
                    'goods_id'     => $barang->id,
                    'goods_code'   => $barang->goods_code,
                    'goods_name'   => $barang->goods_name,
                    'category'     => $barang->category,
                    'stock'        => $barang->stock,
                    'unit'         => $barang->unit,
                    'location'     => $barang->location,
                    'buy_price'    => $barang->buy_price,
                    'selling_price' => $barang->selling_price,
                    'description'   => $barang->description,
                    'old_status'   => $barang->getOriginal('goods_status'),
                    'new_status'   => $barang->goods_status,
                    'form'         => $barang->form,
                    'changed_by'   => Auth::id(),
                    'note'         => $barang->note ?? null,
                    'action'       => $action,
                ]);
            }
        });

        static::deleted(function ($barang) {
            BarangHistory::create([
                'goods_id'     => $barang->id,
                'goods_code'   => $barang->goods_code,
                'goods_name'   => $barang->goods_name,
                'category'     => $barang->category,
                'stock'        => $barang->stock,
                'unit'         => $barang->unit,
                'location'     => $barang->location,
                'buy_price'    => $barang->buy_price,
                'selling_price' => $barang->selling_price,
                'description'   => $barang->description,
                'old_status'   => $barang->goods_status,
                'new_status'   => 'dihapus',
                'changed_by'   => Auth::id(),
                'note'         => $barang->note ?? null,
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

    // Accessor for selling price (harga jual = kolom selling_price di database)
    public function getHargaJualAttribute()
    {
        return (float) ($this->attributes['selling_price'] ?? 0);
    }
}
