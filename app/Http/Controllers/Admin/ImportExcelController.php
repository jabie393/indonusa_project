<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ImportExcelController extends Controller
{
    // Tampilkan daftar barang (purchase request)
    public function index()
    {
        $barangs = Barang::all();
        $kategoriList = Barang::KATEGORI; // Ambil daftar kategori dari model Barang

        return view('admin.import-excel.index', compact('barangs', 'kategoriList'));
    }

    // saat file diupload via AJAX -> simpan file & kembalikan preview
    public function store(Request $request)
    {
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            $folder = 'imports';
            $path = $file->store($folder, 'public');
            $fullPath = storage_path('app/public/' . $path);

            // baca sheet pertama sebagai array
            $sheets = Excel::toArray(null, $fullPath);
            $sheet = $sheets[0] ?? [];

            $headers = [];
            $sampleRows = [];

            if (count($sheet) > 0) {
                // pertama dianggap header
                $headers = array_map(function($h){ return is_null($h) ? '' : trim((string)$h); }, $sheet[0]);

                // ambil sample rows mulai dari index 1 (baris 2 excel) ke bawah
                $maxPreview = min(count($sheet) - 1, 50); // batasi preview untuk performa
                for ($i = 1; $i <= $maxPreview; $i++) {
                    $sampleRows[] = $sheet[$i];
                }
            }

            return response()->json([
                'message' => 'File uploaded',
                'path' => $path,
                'url' => asset('storage/' . $path),
                'headers' => $headers,
                'rows' => $sampleRows,
            ], 200);
        }

        return response()->json(['message' => 'No file uploaded'], 422);
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
                        $kode = 'IMP' . substr(uniqid(), -6);
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

                    Barang::create($payload);
                    $created++;
                }

                DB::commit();

                // optional: hapus file excel setelah import sukses (jika ada path)
                $path = $request->input('import_file_path');
                if (!empty($path) && Storage::disk('public')->exists($path)) {
                    try { Storage::disk('public')->delete($path); } catch (\Throwable $e) { \Log::warning('Delete import file failed: '.$e->getMessage()); }
                }

                $msg = "Import selesai. Berhasil: $created. Error: " . count($errors);
                return redirect()->route('import-excel.index')->with(['title' => 'Import Selesai', 'text' => $msg]);
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
                $kode = 'IMP' . uniqid();
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
                Barang::create($payload);
                $created++;
            } catch (\Exception $e) {
                $errors[] = "Baris $i: " . $e->getMessage();
            }
        }

        $msg = "Import selesai. Berhasil: $created. Error: " . count($errors);

        return redirect()->route('import-excel.index')->with(['title' => 'Import Selesai', 'text' => $msg]);
    }
}
