<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
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
        $status = session('warehouse_filter_status', 'masuk');

        if ($status === 'defect') {
            $goods = Barang::where('status_barang', 'ditinjau_supervisor');
        } else {
            $goods = Barang::where('status_barang', 'masuk');
        }

        if ($query) {
            $goods = $goods->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%")
                    ->orWhere('kategori', 'like', "%{$query}%");
            });
        }

        $goods = $goods->paginate($perPage)->appends($request->except('page'));
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        // Kirimkan barang pertama sebagai contoh (opsional)
        $barang = $goods->first();

        return view('admin.warehouse.index', compact('goods', 'kategoriList', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255|unique:goods,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'stok' => 'required|integer',
            'satuan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Set default deskripsi jika tidak diisi
        if (empty($validated['deskripsi'])) {
            $validated['deskripsi'] = '"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."';
        }

        // Simpan id user yang submit ke kolom 'form'
        $validated['form'] = Auth::id();

        $validated['status_barang'] = 'masuk'; // Set status_barang masuk
        $validated['tipe_request'] = 'primary'; // Set tipe_request primary

        $barang = Barang::create($validated);

        if ($request->hasFile('gambar')) {
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil ditambahkan!']);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        // Jika request memiliki harga_diajukan, ini adalah pelaporan barang rusak dari Warehouse
        if ($request->has('harga_diajukan')) {
            $request->validate([
                'harga_diajukan' => 'required|numeric|min:0',
                'stok_diajukan' => 'required|integer|min:1',
                'alasan_pengajuan' => 'required|string',
            ]);

            if ($barang->stok < $request->input('stok_diajukan')) {
                return redirect()->back()->with(['title' => 'Gagal', 'text' => 'Stok barang tidak cukup untuk dilaporkan rusak.', 'icon' => 'error']);
            }

            // 1. Kurangi stok barang asli
            $barang->stok -= $request->input('stok_diajukan');
            $barang->save();

            // 2. Buat barang baru dengan status ditinjau_supervisor
            $defectData = $barang->toArray();
            unset($defectData['id'], $defectData['created_at'], $defectData['updated_at']);
            
            $defectData['nama_barang'] = "(defect) " . $barang->nama_barang;
            $defectData['kode_barang'] = $this->generateUniqueKodeBarang($barang->kategori);
            $defectData['status_barang'] = 'ditinjau_supervisor';
            $defectData['stok'] = $request->input('stok_diajukan');
            $defectData['harga'] = $request->input('harga_diajukan');
            $defectData['alasan_pengajuan'] = $request->input('alasan_pengajuan');
            $defectData['form'] = Auth::id();

            Barang::create($defectData);

            return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang rusak berhasil dilaporkan dan menunggu tinjauan supervisor.']);
        }

        $validated = $request->validate([
            'status_listing' => 'required|in:listing,non listing',
            'kode_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', Barang::KATEGORI),
            'stok' => 'required|integer',
            'satuan' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('gambar')) {
            // Upload gambar baru
            $folder = 'barang/' . $barang->id;
            $path = $request->file('gambar')->store($folder, 'public');
            $barang->gambar = $path;
            $barang->save();

            // Hapus gambar lama jika ada
            if ($oldGambar && \Storage::disk('public')->exists($oldGambar)) {
                \Storage::disk('public')->delete($oldGambar);
            }
        }

        return redirect()->route('warehouse.index')->with(['title' => 'Berhasil', 'text' => 'Barang berhasil diupdate!']);
    }

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
        } while (Barang::where('kode_barang', $kodeBarang)->exists());

        return $kodeBarang;
    }
}
