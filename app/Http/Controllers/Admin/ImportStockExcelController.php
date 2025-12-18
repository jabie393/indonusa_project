<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ImportStockExcelController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.import-stock-excel.index', compact('barangs', 'kategoriList'));
    }

    // Export data barang dengan status masuk
    public function export()
    {
        return Excel::download(new \App\Exports\BarangExport, 'stock_barang.xlsx');
    }

    // saat file diupload via AJAX -> simpan file & kembalikan preview
    public function store(Request $request)
    {
        if (! $request->hasFile('excel')) {
            return response()->json(['message' => 'No file uploaded'], 422);
        }

        $file = $request->file('excel');
        if (! $file->isValid()) {
            return response()->json(['message' => 'Uploaded file is invalid'], 422);
        }

        // gunakan nama asli klien, hindari karakter berbahaya
        $originalName = preg_replace('/[^A-Za-z0-9\-\_\.\s()]/', '', $file->getClientOriginalName());
        $originalName = trim($originalName);
        if ($originalName === '') {
            $originalName = 'import.xlsx';
        }

        $dir = 'imports';
        $nameOnly = pathinfo($originalName, PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension() ?: 'xlsx';
        $storedName = $nameOnly . '.' . $ext;

        // HAPUS semua file yang ada di folder imports terlebih dahulu
        try {
            if (Storage::disk('public')->exists($dir)) {
                $existingFiles = Storage::disk('public')->files($dir);
                foreach ($existingFiles as $ef) {
                    // jangan gagal keseluruhan jika satu file gagal dihapus
                    try {
                        Storage::disk('public')->delete($ef);
                    } catch (\Throwable $ex) {
                        \Log::warning("Failed to delete existing import file {$ef}: " . $ex->getMessage());
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Cleaning imports folder failed: '.$e->getMessage());
            // lanjutkan saja, karena kita masih bisa mencoba menyimpan file baru
        }

        // simpan file baru menggunakan nama asli (sekarang folder kosong sehingga aman)
        $storedPath = $file->storeAs($dir, $storedName, 'public'); // returns e.g. "imports/filename.xlsx"

        // coba baca preview (headers + rows) menggunakan maatwebsite/excel
        $fullPath = storage_path('app/public/' . $storedPath);
        $headers = [];
        $rows = [];

        try {
            $sheets = Excel::toArray(null, $fullPath);
            $sheet = $sheets[0] ?? [];
            if (count($sheet) > 0) {
                $headers = array_map(function($h){ return is_null($h) ? '' : trim((string)$h); }, $sheet[0]);
                $rows = array_slice($sheet, 1);
            }
        } catch (\Throwable $e) {
            // jika gagal baca preview, tetap kirim path/url agar front-end tetap bekerja
            \Log::warning('Preview read failed: '.$e->getMessage());
        }

        return response()->json([
            'message' => 'Uploaded',
            'path' => $storedPath,
            'url'  => Storage::url($storedPath),
            'headers' => $headers,
            'rows' => $rows,
        ], 201);
    }

    // proses import ketika form submit: gunakan file yang sudah diupload + mapping dari user
    public function import(Request $request)
    {
        // prefer rows[] yang dikirim dari form (di-inject oleh excel-upload.js)
        $formRows = $request->input('rows', null);
        $created = 0;
        $errors = [];

        if (is_array($formRows) && count($formRows) > 0) {
            DB::beginTransaction();
            try {
                foreach ($formRows as $i => $r) {
                    // setiap $r biasanya berisi keys: kode_barang, nama_barang, kategori, stok
                    $kode = $r['kode_barang'] ?? null;
                    if (empty($kode)) {
                        // Jika kode kosong di excel, generate baru (kasus item baru tanpa kode)
                        $kode = 'IMP' . substr(uniqid(), -6);
                    }

                    $stok = isset($r['stok']) ? (int)$r['stok'] : 0;

                    // Cek apakah barang dengan kode tersebut sudah ada
                    $existingBarang = Barang::where('kode_barang', $kode)->first();

                    if ($existingBarang) {
                        // --- LOGIKA ADD STOCK (Replicate) ---
                        // Copy data baru dengan stok baru dan status_barang 'ditinjau'
                        $copyData = $existingBarang->replicate();
                        $copyData->stok = $stok;
                        $copyData->status_barang = 'ditinjau';
                        $copyData->tipe_request = 'new_stock';
                        $copyData->form = Auth::id();

                        // Generate kode_barang unik untuk request ini (Code#1, Code#2, dst)
                        $originalKode = $existingBarang->kode_barang;
                        $newKode = $originalKode;
                        $idx = 1;
                        while (\App\Models\Barang::where('kode_barang', $newKode)->exists()) {
                            $newKode = $originalKode . '#' . $idx;
                            $idx++;
                        }
                        $copyData->kode_barang = $newKode;
                        $copyData->save();

                    } else {
                        // --- LOGIKA NEW ITEM (Primary) ---
                        // Barang benar-benar baru, belum ada di database
                        $payload = [
                            'tipe_request'  => 'primary', // Item induk baru
                            'status_barang' => 'ditinjau',
                            'kode_barang'   => $kode,
                            'nama_barang'   => $r['nama_barang'] ?? 'Unnamed',
                            'kategori'      => $r['kategori'] ?? null,
                            'stok'          => $stok,
                            'satuan'        => $r['satuan'] ?? 'Unit',
                            'deskripsi'     => $r['deskripsi'] ?? '-',
                            'harga'         => isset($r['harga']) ? (float)$r['harga'] : 0,
                            'lokasi'        => $r['lokasi'] ?? '-',
                            'form'          => Auth::id(),
                        ];

                        Barang::create($payload);
                    }

                    $created++;
                }

                DB::commit();

                // optional: hapus file excel setelah import sukses (jika ada path)
                $path = $request->input('import_file_path');
                if (!empty($path) && Storage::disk('public')->exists($path)) {
                    try { Storage::disk('public')->delete($path); } catch (\Throwable $e) { \Log::warning('Delete import file failed: '.$e->getMessage()); }
                }

                $msg = "Import selesai. Berhasil: $created. Error: " . count($errors);
                return redirect()->route('import-stock-excel.index')->with(['title' => 'Import Selesai', 'text' => $msg]);
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->with(['title' => 'Error', 'text' => 'Import gagal: '.$e->getMessage()]);
            }
        }

        return back()->with(['title' => 'Error', 'text' => 'Tidak ada data untuk diimpor.']);
    }
}
