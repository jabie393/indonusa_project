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
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('tipe_customer', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate($perPage);
        $customers->appends(['search' => $search, 'perPage' => $perPage]);

        $salesUsers = User::where('role', 'Sales')->get();
        $pics = DB::table('pics')->get();

        return view('admin.customer.index', compact('customers', 'salesUsers', 'pics'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'term_of_payments' => 'nullable|string|max:100',
            'kredit_limit' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'pics' => 'required|array',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
        ]);

        // Buat customer baru
        $customer = Customer::create([
            'nama_customer' => $validatedData['name'],
            'npwp' => $validatedData['npwp'] ?? null,
            'term_of_payments' => $validatedData['term_of_payments'] ?? null,
            'kredit_limit' => $validatedData['kredit_limit'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'telepon' => $validatedData['telepon'] ?? null,
            'alamat' => $validatedData['alamat'] ?? null,
            'kota' => $validatedData['kota'] ?? null,
            'provinsi' => $validatedData['provinsi'] ?? null,
            'kode_pos' => $validatedData['kode_pos'] ?? null,
            'tipe_customer' => ucfirst(strtolower($validatedData['tipe_customer'])),
            'created_by' => auth()->id(),
        ]);

        // Proses setiap PIC yang dikirim
        foreach ($validatedData['pics'] as $pic) {
            // Jika data adalah JSON string, decode terlebih dahulu
            if (is_string($pic)) {
                $decodedPic = json_decode($pic, true);

                // Jika berhasil di-decode, gunakan data yang di-decode
                if (json_last_error() === JSON_ERROR_NONE) {
                    $pic = $decodedPic;
                } else {
                    // Jika tidak bisa di-decode, anggap sebagai PIC baru
                    $newPic = Pic::create([
                        'name' => $pic,
                    ]);
                    $customer->pics()->attach($newPic->id, ['pic_type' => 'Pic']);
                    continue;
                }
            }

            // Jika data memiliki ID dan tipe, periksa apakah sudah ada di database
            if (isset($pic['id']) && isset($pic['type'])) {
                if ($pic['type'] === 'Pic') {
                    // Periksa apakah PIC sudah ada di database
                    $existingPic = Pic::find($pic['id']);
                    if ($existingPic) {
                        $customer->pics()->attach($pic['id'], ['pic_type' => 'Pic']);
                        continue;
                    }
                } elseif ($pic['type'] === 'User') {
                    // Periksa apakah User sudah ada di database
                    $existingUser = User::find($pic['id']);
                    if ($existingUser) {
                        $customer->pics()->attach($pic['id'], ['pic_type' => 'User']);
                        continue;
                    }
                }
            }

            // Jika data memiliki properti `newTag`, buat entri baru
            if (isset($pic['newTag']) && $pic['newTag'] === true) {
                $newPic = Pic::create([
                    'name' => $pic['id'],
                ]);
                $customer->pics()->attach($newPic->id, ['pic_type' => 'Pic']);
            }
        }

        return redirect()->route('customer.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil ditambahkan.']);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'term_of_payments' => 'nullable|string|max:100',
            'kredit_limit' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'pics' => 'nullable|array',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
        ]);

        // Temukan customer berdasarkan ID
        $customer = Customer::findOrFail($validatedData['id']);

        // Update data customer
        $customer->update([
            'nama_customer' => $validatedData['name'],
            'npwp' => $validatedData['npwp'] ?? null,
            'term_of_payments' => $validatedData['term_of_payments'] ?? null,
            'kredit_limit' => $validatedData['kredit_limit'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'telepon' => $validatedData['telepon'] ?? null,
            'alamat' => $validatedData['alamat'] ?? null,
            'kota' => $validatedData['kota'] ?? null,
            'provinsi' => $validatedData['provinsi'] ?? null,
            'kode_pos' => $validatedData['kode_pos'] ?? null,
            'tipe_customer' => ucfirst(strtolower($validatedData['tipe_customer'])),
        ]);

        // Sinkronisasi PICs
        $customer->pics()->detach(); // Hapus semua hubungan PIC sebelumnya

        if (!empty($validatedData['pics'])) {
            foreach ($validatedData['pics'] as $pic) {
                try {
                    if (is_string($pic)) {
                        $decodedPic = json_decode($pic, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $pic = $decodedPic;
                        } else {
                            $newPic = Pic::create(['name' => $pic]);
                            $customer->pics()->attach($newPic->id, ['pic_type' => 'Pic']);
                            continue;
                        }
                    }

                    if (isset($pic['id']) && isset($pic['type'])) {
                        if ($pic['type'] === 'Pic') {
                            $existingPic = Pic::find($pic['id']);
                            if ($existingPic) {
                                $customer->pics()->attach($pic['id'], ['pic_type' => 'Pic']);
                                continue;
                            }
                        } elseif ($pic['type'] === 'User') {
                            $existingUser = User::find($pic['id']);
                            if ($existingUser) {
                                $customer->pics()->attach($pic['id'], ['pic_type' => 'User']);
                                continue;
                            }
                        }
                    }

                    if (isset($pic['newTag']) && $pic['newTag'] === true) {
                        $newPic = Pic::create(['name' => $pic['id']]);
                        $customer->pics()->attach($newPic->id, ['pic_type' => 'Pic']);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error processing PIC: ' . $e->getMessage(), ['pic' => $pic]);
                }
            }
        }

        return redirect()->route('customer.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil diperbarui.']);
    }

    // Ambil langsung dari tabel customer_pics (mengembalikan array objek: id, name, position, pivot.pic_type, pivot.created_at)
    public function getPics($id)
    {
        if (!Schema::hasTable('customer_pics')) {
            return response()->json([]);
        }

        $rows = DB::table('customer_pics')->where('customer_id', $id)->get();

        $result = $rows->map(function ($r) {
            if ($r->pic_type === 'Pic') {
                $pic = DB::table('pics')->where('id', $r->pic_id)->first();
                if ($pic) {
                    return (object)[
                        'id' => $pic->id,
                        'name' => $pic->name,
                        'position' => $pic->position ?? null,
                        'pivot' => (object)[
                            'pic_type' => 'Pic',
                            'created_at' => $r->created_at,
                        ],
                    ];
                }
            } elseif ($r->pic_type === 'User') {
                $user = DB::table('users')->where('id', $r->pic_id)->first();
                if ($user) {
                    return (object)[
                        'id' => $user->id,
                        'name' => $user->name,
                        'position' => null,
                        'pivot' => (object)[
                            'pic_type' => 'User',
                            'created_at' => $r->created_at,
                        ],
                    ];
                }
            }
            return null;
        })->filter()->values();

        return response()->json($result);
    }
}
