<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;
use Illuminate\Support\Facades\Auth;

class GoodsInController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $goods = Goods::all();
        $kategoriList = Goods::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.goods-in.index', compact('goods', 'kategoriList'));
    }

    // Simpan barang baru
    public function store(Request $request)
    {

        // If an Excel file is uploaded via AJAX (field name `excel`), store it and return JSON.
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            // store in public disk under imports/ with automatic filename
            $folder = 'imports';
            $path = $file->store($folder, 'public');

            return response()->json([
                'message' => 'File uploaded',
                'path' => $path,
                'url' => asset('storage/' . $path),
            ], 200);
        }

        $validated = $request->validate([
            'goods_code' => 'required|string|max:255|unique:goods,goods_code',
            'goods_name' => 'required|string|max:255',
            'category' => 'required|in:' . implode(',', Goods::KATEGORI),
            'unit' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Default value untuk master barang baru (kosongan)
        $validated['status_listing'] = 'listing';
        $validated['stock'] = 0;
        $validated['buy_price'] = 0.00;
        $validated['selling_price'] = 0.00;

        // Set default deskripsi jika tidak diisi
        if (empty($validated['description'])) {
            $validated['description'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        }

        // Simpan id user yang submit ke kolom 'form'
        $validated['form'] = Auth::id();
        $validated['goods_status'] = 'approved';
        $validated['request_type'] = 'primary';

        $barang = Goods::create($validated);

        if ($request->hasFile('image')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('image')->store($folder, 'public');
            $barang->image = $path;
            $barang->save();
        }

        return redirect()->route('goods-in.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan.']);
    }
}
