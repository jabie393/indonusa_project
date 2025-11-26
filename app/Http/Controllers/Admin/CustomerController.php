<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;

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
            'kode_pos' => 'nullable|string|max:10',
            'pic' => 'nullable|string|max:100',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
        ]);

        $validatedData['tipe_customer'] = ucfirst(strtolower($validatedData['tipe_customer']));

        $customer = new Customer();
        $customer->nama_customer = $validatedData['name'];
        $customer->npwp = $validatedData['npwp'] ?? null;
        $customer->term_of_payments = $validatedData['term_of_payments'] ?? null;
        $customer->kredit_limit = $validatedData['kredit_limit'] ?? null;
        $customer->email = $validatedData['email'] ?? null;
        $customer->telepon = $validatedData['telepon'] ?? null;
        $customer->alamat = $validatedData['alamat'] ?? null;
        $customer->kota = $validatedData['kota'] ?? null;
        $customer->provinsi = $validatedData['provinsi'] ?? null;
        $customer->kode_pos = $validatedData['kode_pos'] ?? null;
        $customer->pic = $validatedData['pic'] ?? null;
        $customer->tipe_customer = $validatedData['tipe_customer'];
        $customer->created_by = auth()->id();
        $customer->save();

        return redirect()->back()->with('success', 'Customer berhasil ditambahkan.');
    }
}
