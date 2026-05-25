<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\GoodsReceipt;
use App\Models\BarangHistory;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        // Jika ada status di URL, simpan ke session dan redirect ke URL bersih
        if ($request->has('status')) {
            session(['warehouse_filter_status' => $request->input('status')]);
            return redirect()->route('warehouse.index', $request->except('status'));
        }

        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $goods = Barang::where('goods_status', 'masuk');

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page'));
        $kategoriList = Barang::KATEGORI;

        $barang = $goods->first();

        return view('admin.warehouse.index', compact('goods', 'kategoriList', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'goods_code' => 'required|string|max:255|unique:goods,goods_code',
            'goods_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer',
            'unit' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        if (empty($validated['description'])) {
            $validated['description'] = '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."';
        }

        $validated['form'] = Auth::id();
        $validated['goods_status'] = 'masuk';
        $validated['request_type'] = 'primary';

        $barang = Barang::create($validated);

        if ($request->hasFile('image')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('image')->store($folder, 'public');
            $barang->image = $path;
            $barang->save();
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        // If selling_price is present, handle price update (General Affair)
        if ($request->has('selling_price')) {
            $validated = $request->validate([
                'selling_price' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $oldPrice = $barang->selling_price;
            $barang->selling_price = $validated['selling_price'];
            $barang->save();

            BarangHistory::create([
                'goods_id' => $barang->id,
                'goods_code' => $barang->goods_code,
                'goods_name' => $barang->goods_name,
                'category' => $barang->category,
                'stock' => $barang->stock,
                'unit' => $barang->unit,
                'location' => $barang->location,
                'buy_price' => $barang->buy_price,
                'selling_price' => $barang->selling_price,
                'old_status' => $barang->goods_status,
                'new_status' => $barang->goods_status,
                'changed_by' => Auth::id(),
                'note' => $validated['note'] ?? 'Perubahan harga jual dari ' . ($oldPrice ?? 0) . ' ke ' . $barang->selling_price,
                'action' => 'harga jual diubah dari Rp ' . number_format($oldPrice ?? 0, 0, ',', '.') . ' ke Rp ' . number_format($barang->selling_price, 0, ',', '.'),
                'form' => Auth::id(),
                'changed_at' => now(),
            ]);

            return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Harga jual berhasil diupdate!']);
        }

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileKey = $request->hasFile('image') ? 'image' : ($request->hasFile('gambar') ? 'gambar' : null);

        if ($fileKey) {
            $oldGambar = $barang->image;
            
            $folder = 'barang/' . $barang->id;
            $path = $request->file($fileKey)->store($folder, 'public');
            $barang->image = $path;
            $barang->save();

            if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                \Storage::disk('public')->delete($oldGambar);
            }
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Foto barang berhasil diupdate!']);
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $folder = 'barang/' . $barang->id;
        if (\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->deleteDirectory($folder);
        }

        $barang->delete();

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus!']);
    }

    private function generateUniqueKodeBarang($kategori)
    {
        $kategoriSingkatan = [
            'HANDTOOLS' => 'HT',
            'ADHESIVE AND SEALANT' => 'AS',
            'AUTOMOTIVE EQUIPMENT' => 'AE',
            'CLEANING' => 'CLN',
            'COMPRESSOR' => 'CMP',
            'CONSTRUCTION' => 'CST',
            'CUTTING TOOLS' => 'CT',
            'LIGHTING' => 'LTG',
            'FASTENING' => 'FST',
            'GENERATOR' => 'GEN',
            'HEALTH CARE EQUIPMENT' => 'HCE',
            'HOSPITALITY' => 'HSP',
            'HYDRAULIC TOOLS' => 'HYD',
            'MARKING MACHINE' => 'MM',
            'MATERIAL HANDLING EQUIPMENT' => 'MHE',
            'MEASURING AND TESTING EQUIPMENT' => 'MTE',
            'METAL CUTTING MACHINERY' => 'MCM',
            'PACKAGING' => 'PKG',
            'PAINTING AND COATING' => 'PC',
            'PNEUMATIC TOOLS' => 'PN',
            'POWER TOOLS' => 'PT',
            'SAFETY AND PROTECTION EQUIPMENT' => 'SPE',
            'SECURITY' => 'SEC',
            'SHEET METAL MACHINERY' => 'SMM',
            'STORAGE SYSTEM' => 'STS',
            'WELDING EQUIPMENT' => 'WLD',
            'WOODWORKING EQUIPMENT' => 'WWE',
            'MISCELLANEOUS' => 'MSC',
            'OTHER CATEGORIES' => 'OC',
        ];

        $singkatan = $kategoriSingkatan[$kategori] ?? 'UNK';
        
        do {
            $randomNumber = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $kodeBarang = "{$singkatan}-{$randomNumber}";
        } while (Barang::where('goods_code', $kodeBarang)->exists());

        return $kodeBarang;
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
