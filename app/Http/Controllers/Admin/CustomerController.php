<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Pic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Customer::query();

        if (Schema::hasTable('customer_pics')) {
            $query->with('pics', 'users');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('alamat_penagihan', 'like', "%{$search}%")
                  ->orWhere('alamat_pengiriman', 'like', "%{$search}%")
                  ->orWhere('tipe_customer', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        // Ensure pics are loaded
        $query->with('pics');

        $customers = $query->paginate($perPage);
        $customers->appends(['search' => $search, 'perPage' => $perPage]);

        $salesUsers = User::where('role', 'Sales')->get(); // Mengambil data user dengan role Sales
        
        // Pics sekarang spesifik per customer, jadi mungkin tidak butuh list semua pics global untuk dropdown 'attach' existing, 
        // kecuali UI-nya masih butuh. Tapi karena 1 PIC = 1 Customer, listing all pics untuk attach ke customer lain jadi tidak valid (steal ownership).
        // Kita biarkan kosong atau hanya pic yang belum punya customer (jika ada).
        // Untuk sekarang kita tidak kirim $pics global untuk menghindari kebingungan, atau kirim kosong.
        $pics = []; 

        return view('admin.customer.index', compact('customers', 'salesUsers', 'pics'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'term_of_payments' => 'nullable|integer|min:0',
            'kredit_limit' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat_penagihan' => 'nullable|string|max:255',
            'alamat_pengiriman' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'pics' => 'required|array', // Array of PIC definitions
            'pics.*.name' => 'required|string',
            'pics.*.phone' => 'required|string', // Sesuai blade required
            'pics.*.email' => 'nullable|email',
            'pics.*.position' => 'nullable|string',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
        ]);

        DB::beginTransaction();
        try {
            // Buat customer baru
            $customer = Customer::create([
                'nama_customer' => $validatedData['name'],
                'npwp' => $validatedData['npwp'] ?? null,
                'term_of_payments' => $validatedData['term_of_payments'] ?? null,
                'kredit_limit' => $validatedData['kredit_limit'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'telepon' => $validatedData['telepon'] ?? null,
                'alamat_penagihan' => $validatedData['alamat_penagihan'] ?? null,
                'alamat_pengiriman' => $validatedData['alamat_pengiriman'] ?? null,
                'kota' => $validatedData['kota'] ?? null,
                'provinsi' => $validatedData['provinsi'] ?? null,
                'kode_pos' => $validatedData['kode_pos'] ?? null,
                'tipe_customer' => ucfirst(strtolower($validatedData['tipe_customer'])),
                'created_by' => auth()->id(),
            ]);

            // Proses setiap PIC yang dikirim
            foreach ($validatedData['pics'] as $picData) {
                // $picData sudah divalidasi sebagai array dengan keys name, phone, email, position
                // (atau string jika fallback legacy masih ada, tapi kita utamakan array struktur baru)

                $name = $picData['name'] ?? null;
                
                // Fallback untuk legacy/select2 jika data dikirim sebagai string/json (opsional, jaga-jaga)
                if (empty($name) && is_string($picData)) {
                     $decoded = json_decode($picData, true);
                     if (json_last_error() === JSON_ERROR_NONE) {
                         $name = $decoded['name'] ?? ($decoded['text'] ?? null);
                     } else {
                         $name = $picData;
                     }
                }

                if ($name) {
                    Pic::create([
                        'customer_id' => $customer->id,
                        'name' => $name,
                        'phone' => $picData['phone'] ?? null,
                        'email' => $picData['email'] ?? null,
                        'position' => $picData['position'] ?? null,
                    ]);
                }
            }
            
            DB::commit();

            // Return JSON for AJAX requests, redirect for normal requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil ditambahkan.',
                    'customer' => [
                        'id' => $customer->id,
                        'nama_customer' => $customer->nama_customer,
                        'email' => $customer->email,
                        'telepon' => $customer->telepon,
                        'kota' => $customer->kota,
                    ],
                ]);
            }
            
            return redirect()->route('customer.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil ditambahkan.']);
            
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan customer: ' . $e->getMessage(),
                ], 422);
            }
            
            return redirect()->back()->withErrors('Gagal menyimpan customer: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'term_of_payments' => 'nullable|integer|min:0',
            'kredit_limit' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat_penagihan' => 'nullable|string|max:255',
            'alamat_pengiriman' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'pics' => 'nullable|array',
            'pics.*.id' => 'nullable|exists:pics,id', // Validasi ID jika ada
            'pics.*.name' => 'required|string',
            'pics.*.phone' => 'required|string',
            'pics.*.email' => 'nullable|email',
            'pics.*.position' => 'nullable|string',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($validatedData['id']);

            $customer->update([
                'nama_customer' => $validatedData['name'],
                'npwp' => $validatedData['npwp'] ?? null,
                'term_of_payments' => $validatedData['term_of_payments'] ?? null,
                'kredit_limit' => $validatedData['kredit_limit'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'telepon' => $validatedData['telepon'] ?? null,
                'alamat_penagihan' => $validatedData['alamat_penagihan'] ?? null,
                'alamat_pengiriman' => $validatedData['alamat_pengiriman'] ?? null,
                'kota' => $validatedData['kota'] ?? null,
                'provinsi' => $validatedData['provinsi'] ?? null,
                'kode_pos' => $validatedData['kode_pos'] ?? null,
                'tipe_customer' => ucfirst(strtolower($validatedData['tipe_customer'])),
            ]);

            // Logic Sinkronisasi PIC One-to-Many
            // 1. Ambil semua ID PIC yang ada di request (yang merupakan PIC lama yang dipertahankan/diedit)
            $submittedPicIds = [];
            
            if (!empty($validatedData['pics'])) {
                foreach ($validatedData['pics'] as $picData) {
                    if (is_string($picData)) {
                        $decoded = json_decode($picData, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $picData = $decoded;
                        } else {
                            // String biasa -> create new PIC
                            $newPic = Pic::create([
                                'customer_id' => $customer->id,
                                'name' => $picData
                            ]);
                            $submittedPicIds[] = $newPic->id;
                            continue;
                        }
                    }

                    // Handle Array data
                    if (isset($picData['id']) && $picData['id']) {
                        // Ini kemungkinan existing PIC atau PIC yang dikirim dengan ID
                        // Pastikan PIC ini milik customer ini (security check)
                        $existingPic = Pic::where('id', $picData['id'])->where('customer_id', $customer->id)->first();
                        
                        if ($existingPic) {
                            // Update existing
                            $existingPic->update([
                                'name' => $picData['name'] ?? $existingPic->name,
                                'phone' => $picData['phone'] ?? $existingPic->phone,
                                'email' => $picData['email'] ?? $existingPic->email,
                                'position' => $picData['position'] ?? $existingPic->position,
                            ]);
                            $submittedPicIds[] = $existingPic->id;
                        } else {
                            // Jika ID dikirim tapi bukan milik customer ini (atau tidak ketemu), 
                            // bisa jadi logika 'assign' pic existing ke customer ini (steal) 
                            // atau abaikan ID dan create baru.
                            // Sesuai requirement "1 pics cuma terhubung dengan 1 customer", kita asumsikan create baru jika id invalid context,
                            // tapi lebih aman create baru untuk menghindari konflik
                             $newPic = Pic::create([
                                'customer_id' => $customer->id,
                                'name' => $picData['name'] ?? 'Unknown',
                                'phone' => $picData['phone'] ?? null,
                                'email' => $picData['email'] ?? null,
                                'position' => $picData['position'] ?? null,
                            ]);
                            $submittedPicIds[] = $newPic->id;
                        }
                    } else {
                        // Tidak ada ID -> Create New
                        $newPic = Pic::create([
                            'customer_id' => $customer->id,
                            'name' => $picData['name'] ?? ($picData['text'] ?? ($picData['id'] ?? 'Unknown')), // Fallback parsing
                            'phone' => $picData['phone'] ?? null,
                            'email' => $picData['email'] ?? null,
                            'position' => $picData['position'] ?? null,
                        ]);
                        $submittedPicIds[] = $newPic->id;
                    }
                }
            }

            // Hapus PIC milik customer ini yang tidak ada di submittedPicIds
            $customer->pics()->whereNotIn('id', $submittedPicIds)->delete();

            DB::commit();
            return redirect()->route('customer.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil diperbarui.']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal memperbarui customer: ' . $e->getMessage())->withInput();
        }
    }

    public function getPics($id)
    {
        $pics = Pic::where('customer_id', $id)->get();
        return response()->json($pics);
    }
}
