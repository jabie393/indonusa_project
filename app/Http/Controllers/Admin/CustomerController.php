<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Pic;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all(); // Mengambil semua data customer dari database
        $salesUsers = User::where('role', 'Sales')->get(); // Mengambil data user dengan role Sales
        $pics = \DB::table('pics')->get(); // Mengambil data dari tabel pics

        return view('admin.customer.index', compact('customers', 'salesUsers', 'pics')); // Meneruskan data ke view
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
}
