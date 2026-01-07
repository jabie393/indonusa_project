<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\GoodsReceipt;

class GoodsReceiptsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        
        $goods = Barang::where('status_barang', 'masuk');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%")
                    ->orWhere('kategori', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page'));

        return view('admin.goods-receipts.index', compact('goods'));
    }

    public function getLogs($id)
    {
        $logs = GoodsReceipt::with(['supplier', 'approver'])
            ->where('good_id', $id)
            ->orderBy('received_at', 'desc')
            ->get();

        return response()->json($logs);
    }
}
