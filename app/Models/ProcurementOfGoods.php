<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementOfGoods extends Model
{
    protected $table = 'procurement_of_goods';

    protected $fillable = [
        'procurement_number',
        'custom_quotation_id',
        'general_affair_id',
        'warehouse_id',
        'status',
        'notes',
    ];

    public function customQuotation()
    {
        return $this->belongsTo(CustomQuotation::class, 'custom_quotation_id');
    }

    public function items()
    {
        return $this->hasMany(ProcurementOfGoodsItem::class, 'procurement_of_goods_id');
    }

    public function generalAffair()
    {
        return $this->belongsTo(User::class, 'general_affair_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(User::class, 'warehouse_id');
    }

    public static function generateProcurementNumber()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now()->toDateString())->count() + 1;
        return 'PR-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
