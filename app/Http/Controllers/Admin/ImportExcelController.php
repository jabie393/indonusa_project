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

class ImportExcelController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        $existingCodes = Barang::pluck('kode_barang')->toArray();

        return view('admin.import-excel.index', compact('barangs', 'kategoriList', 'existingCodes'));
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
                    // setiap $r biasanya berisi keys: kode_barang, nama_barang, kategori, stok, harga, satuan, status_listing, lokasi, gambar...
                    $kode = $r['kode_barang'] ?? null;
                    if (empty($kode)) {
                        $kode = 'IMP-' . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
                    }

                    $hargaRaw = $r['harga'] ?? null;
                    if ($hargaRaw !== null && $hargaRaw !== '') {
                        $clean = preg_replace('/[^\d\.,-]/', '', (string)$hargaRaw);
                        $clean = str_replace(',', '.', $clean);
                        $harga = floatval($clean);
                    } else {
                        $harga = 0;
                    }

                    $stok = isset($r['stok']) ? (int)$r['stok'] : 0;

                    $payload = [
                        'tipe_request' => 'primary',
                        'status_barang' => 'ditinjau',
                        'status_listing' => $r['status_listing'] ?? 'listing',
                        'kode_barang' => $kode,
                        'nama_barang' => $r['nama_barang'] ?? 'Unnamed',
                        'kategori' => $r['kategori'] ?? null,
                        'stok' => $stok,
                        'satuan' => $r['satuan'] ?? 'pcs',
                        'harga' => $harga,
                        'deskripsi' => $r['deskripsi'] ?? 'Deskripsi otomatis',
                        'gambar' => $r['gambar'] ?? null,
                        'form' => Auth::id(),
                    ];

                    // create barang first so we have id to store images under folder barang/{id}
                    $barang = Barang::create($payload);

                    // store any uploaded images for this row: input name expected rows[{$i}][images][]
                    try {
                        $savedPaths = [];
                        $files = $request->file("rows.{$i}.images");
                        if (is_array($files) && count($files) > 0) {
                            foreach ($files as $f) {
                                if ($f && $f->isValid()) {
                                    $folder = 'barang/' . $barang->id;
                                    $path = $f->store($folder, 'public');
                                    $savedPaths[] = $path;
                                }
                            }
                        }
                        // if any images saved, set gambar to first file (same behavior as GoodsInController)
                        if (!empty($savedPaths)) {
                            $barang->gambar = $savedPaths[0];
                            $barang->save();
                        }
                    } catch (\Throwable $ex) {
                        \Log::warning("Failed storing images for imported row {$i}: ".$ex->getMessage());
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
                return redirect()->route('import-excel.index')
                    ->with('title', 'Import Selesai')
                    ->with('text', $msg)
                    ->with('success', $msg);
            } catch (\Throwable $e) {
                DB::rollBack();
                return back()->with(['title' => 'Error', 'text' => 'Import gagal: '.$e->getMessage()]);
            }
        }

        // fallback: jika tidak ada rows[] di form, gunakan metode lama (baca dari file Excel + mapping indeks)
        $request->validate([
            'import_file_path' => 'required|string',
            'mapping' => 'required|array'
        ]);

        $path = $request->input('import_file_path');
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            return back()->with(['title' => 'Error', 'text' => 'File import tidak ditemukan.']);
        }

        $sheets = Excel::toArray(null, $fullPath);
        $sheet = $sheets[0] ?? [];

        if (count($sheet) <= 1) {
            return back()->with(['title' => 'Error', 'text' => 'Tidak ada data untuk diimpor.']);
        }

        $headers = array_map(function($h){ return is_null($h) ? '' : trim((string)$h); }, $sheet[0]);
        $mapping = $request->input('mapping'); // contoh: ['nama_barang' => 1, 'kategori' => 0, ...]

        $created = 0;
        $errors = [];

        for ($i = 1; $i < count($sheet); $i++) {
            $row = $sheet[$i];

            $data = [];
            $fields = ['kategori','nama_barang','deskripsi','stok','satuan','harga','gambar','status_listing'];

            foreach ($fields as $field) {
                if (isset($mapping[$field]) && $mapping[$field] !== '') {
                    $colIndex = (int)$mapping[$field];
                    $value = $row[$colIndex] ?? null;
                    if (is_string($value)) $value = trim($value);
                    $data[$field] = $value;
                }
            }

            $kode = $request->input('mapping.kode_barang') !== null ? ($row[(int)$request->input('mapping.kode_barang')] ?? null) : null;
            if (empty($kode)) {
                $kode = 'IMP-' . str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            }

            $hargaRaw = $data['harga'] ?? null;
            if ($hargaRaw !== null) {
                $clean = preg_replace('/[^\d\.,-]/', '', (string)$hargaRaw);
                $clean = str_replace(',', '.', $clean);
                $harga = floatval($clean);
            } else {
                $harga = 0;
            }

            $stok = isset($data['stok']) ? (int)$data['stok'] : 0;

            $payload = [
                'tipe_request' => 'primary',
                'status_barang' => 'ditinjau',
                'status_listing' => $data['status_listing'] ?? 'listing',
                'kode_barang' => $kode,
                'nama_barang' => $data['nama_barang'] ?? 'Unnamed',
                'kategori' => $data['kategori'] ?? null,
                'stok' => $stok,
                'satuan' => $data['satuan'] ?? 'pcs',
                'harga' => $harga,
                'deskripsi' => $data['deskripsi'] ?? 'Deskripsi otomatis',
                'gambar' => $data['gambar'] ?? null,
                'form' => Auth::id(),
            ];

            try {
                $barang = Barang::create($payload);
                // try store uploaded files for this row if any (name rows[{$i}][images][])
                try {
                    $savedPaths = [];
                    $files = $request->file("rows.{$i}.images");
                    if (is_array($files) && count($files) > 0) {
                        foreach ($files as $f) {
                            if ($f && $f->isValid()) {
                                $folder = 'barang/' . $barang->id;
                                $path = $f->store($folder, 'public');
                                $savedPaths[] = $path;
                            }
                        }
                    }
                    if (!empty($savedPaths)) {
                        $barang->gambar = $savedPaths[0];
                        $barang->save();
                    }
                } catch (\Throwable $ex) {
                    \Log::warning("Failed storing images for legacy-import row {$i}: ".$ex->getMessage());
                }
                $created++;
            } catch (\Exception $e) {
                $errors[] = "Baris $i: " . $e->getMessage();
            }
        }

        $msg = "Import selesai. Berhasil: $created. Error: " . count($errors);

        return redirect()->route('import-excel.index')
            ->with('title', 'Import Selesai')
            ->with('text', $msg)
            ->with('success', $msg);
    }
}
