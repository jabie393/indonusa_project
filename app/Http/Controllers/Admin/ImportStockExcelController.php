<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportStockExcelController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $goods = Barang::all();
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.import-stock-excel.index', compact('goods', 'kategoriList'));
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
                $rowsRaw = array_slice($sheet, 1);
                
                // Pre-process rows to check existence
                foreach ($rowsRaw as $r) {
                    // Asumsi urutan kolom: 0=Kode, 1=Nama, 2=Kategori, 3=Stok
                    // Perlu mapping dinamis atau asumsi. Sesuai JS: Hardcode map['kode_barang'] = 0
                    $kode = isset($r[0]) ? trim((string)$r[0]) : '';
                    $isKnown = false;
                    
                    if (!empty($kode)) {
                        $isKnown = Barang::where('goods_code', $kode)->exists();
                    }
                    
                    // Append flag to row data (as extra column or property if object)
                    // Since Excel::toArray returns array of arrays, we handle it carefully.
                    // Client JS expects simple array. We can pass separate metadata or append to row.
                    // Let's modify row structure sent to JSON.
                    
                    // Kita kirim structure baru untuk 'rows' agar lebih robust
                    $rows[] = [
                        'data' => $r,
                        'is_known' => $isKnown
                    ];
                }
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
                $lastBarang = null;
                Barang::withoutEvents(function () use ($formRows, &$created, &$lastBarang) {
                    foreach ($formRows as $i => $r) {
                        $kode = $r['goods_code'] ?? null;
                        if (empty($kode)) {
                            $kode = 'IMP' . substr(uniqid(), -6);
                        }

                        $stok = isset($r['stock']) ? (int)$r['stock'] : 0;

                        if ($stok <= 0) {
                            continue;
                        }

                        $existingBarang = Barang::where('goods_code', $kode)->first();

                        if ($existingBarang) {
                            if (!isset($r['selling_price']) || $r['selling_price'] === '' || $r['selling_price'] === null) {
                                $harga = ($stok > 0) ? (float)$existingBarang->buy_price : 0;
                            } else {
                                $harga = (float)$r['selling_price'];
                            }

                            $copyData = $existingBarang->replicate();

                            $deskripsi = $r['description'] ?? $existingBarang->description;
                            if (empty($deskripsi)) {
                                $deskripsi = $existingBarang->goods_name;
                            }

                            $copyData->description = $deskripsi;
                            $copyData->stock = $stok;
                            $copyData->buy_price = $harga;
                            $copyData->goods_status = 'ditinjau';
                            $copyData->request_type = 'new_stock';
                            $copyData->form = Auth::id();

                            $originalKode = $existingBarang->goods_code;
                            $newKode = $originalKode;
                            $idx = 1;
                            while (\App\Models\Barang::where('goods_code', $newKode)->exists()) {
                                $newKode = $originalKode . '#' . $idx;
                                $idx++;
                            }
                            $copyData->goods_code = $newKode;
                            $copyData->save();

                            $created++;
                            $lastBarang = $copyData;
                        }
                    }
                });

                // Trigger notifikasi hanya SEKALI di akhir proses
                if ($lastBarang) {
                    event(new \App\Events\BarangStatusUpdated($lastBarang));
                }

                DB::commit();

                // optional: hapus file excel setelah import sukses (jika ada path)
                $path = $request->input('import_file_path');
                if (!empty($path) && Storage::disk('public')->exists($path)) {
                    try {
                        Storage::disk('public')->delete($path);
                    } catch (\Throwable $e) {
                        \Log::warning('Delete import file failed: ' . $e->getMessage());
                    }
                }

                $msg = "Import selesai. Berhasil: $created. Error: " . count($errors);
                return redirect()->route('import-stock-excel.index')
                    ->with('title', 'Import Selesai')
                    ->with('text', $msg)
                    ->with('success', $msg);
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->with(['title' => 'Error', 'text' => 'Import gagal: ' . $e->getMessage()]);
            }
        }

        return back()->with(['title' => 'Error', 'text' => 'Tidak ada data untuk diimpor.']);
    }
}
