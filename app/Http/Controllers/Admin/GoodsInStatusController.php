<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;

class GoodsInStatusController extends Controller
{
    // Tampilkan daftar barang (Item Status)
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');
        $goods = Barang::whereIn('goods_status', ['ditinjau', 'ditolak'])
            ->orderByRaw("FIELD(goods_status, 'ditolak', 'ditinjau')")
            ->latest();

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('goods_name', 'like', "%{$query}%")
                    ->orWhere('goods_code', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%");
            });
        }
        $goods = $goods->paginate($perPage)->appends($request->except('page'));
        return view('admin.goods-in-status.index', compact('goods'));
    }

    // Tampilkan detail barang
    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('admin.goods-in-status.show', compact('barang'));
    }

    // Update barang
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        if ($request->has('stock') && !$request->has('goods_name')) {
            // update stok & harga (untuk request baru/penambahan stok)
            $request->validate([
                'stock' => 'required|integer',
                'buy_price' => 'required|numeric|min:0',
            ]);
            $barang->stock = $request->stock;
            $barang->buy_price = $request->buy_price; // Simpan harga beli baru
            $barang->goods_status = 'ditinjau'; // ubah status_barang
            $barang->note = null; // kosongkan kolom catatan
            $barang->save();
        } else {
            // update full barang
            $request->validate([
                'status_listing' => 'required|string',
                'goods_code' => 'required|string|max:255',
                'goods_name' => 'required|string|max:255',
                'category' => 'required|in:' . implode(',', Barang::KATEGORI),
                'stock' => 'required|integer',
                'unit' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
                'buy_price' => 'required|numeric',
                'selling_price' => 'nullable|numeric',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only([
                'status_listing',
                'goods_code',
                'goods_name',
                'category',
                'stock',
                'unit',
                'location',
                'buy_price',
                'selling_price',
                'description'
            ]);

            $data['goods_status'] = 'ditinjau'; // ubah status_barang
            $data['note'] = null; // kosongkan kolom catatan

            $oldGambar = $barang->image;

            $barang->update($data);

            if ($request->hasFile('image')) {
                $folder = 'barang/' . $barang->id;
                $path = $request->file('image')->store($folder, 'public');
                $barang->image = $path;
                $barang->save();

                // Hapus gambar lama jika ada
                if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                    \Storage::disk('public')->delete($oldGambar);
                }
            }
        }

        return redirect()->route('goods-in-status.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diupdate.']);
    }

    // Hapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Hapus folder gambar barang beserta isinya jika ada
        $folder = 'barang/' . $barang->id;
        if (\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->deleteDirectory($folder);
        }

        // Hapus data barang di database
        $barang->delete();

        return redirect()->route('goods-in-status.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil dihapus.']);
    }
}
