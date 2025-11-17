<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * List semua customer
     */
    public function index()
    {
        $customers = Customer::with('createdBy')
            ->latest()
            ->paginate(20);

        return view('admin.sales.customer.index', compact('customers'));
    }

    /**
     * Form untuk membuat customer baru
     */
    public function create()
    {
        return view('admin.sales.customer.create');
    }

    /**
     * Simpan customer baru
     */
    public function store(Request $request)
    {
        // Check if user has permission to create customer
        if (!in_array(Auth::user()->role, ['Sales', 'Admin'])) {
            return back()->withErrors('Anda tidak memiliki izin untuk membuat customer.');
        }

        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tipe_customer' => 'nullable|in:retail,wholesale,distributor',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'nama_customer' => $validated['nama_customer'],
                'email' => $validated['email'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'kota' => $validated['kota'] ?? null,
                'provinsi' => $validated['provinsi'] ?? null,
                'kode_pos' => $validated['kode_pos'] ?? null,
                'tipe_customer' => $validated['tipe_customer'] ?? 'retail',
                'status' => $validated['status'] ?? 'active',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            // Check if this is AJAX request (quick add)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer berhasil ditambahkan',
                    'customer' => $customer,
                ]);
            }

            return redirect()->route('sales.customer.show', $customer->id)
                ->with('success', "Customer {$customer->nama_customer} berhasil dibuat.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal membuat customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Lihat detail customer
     */
    public function show(Customer $customer)
    {
        $customer->load('createdBy', 'updatedBy', 'requestOrders', 'salesOrders');

        return view('admin.sales.customer.show', compact('customer'));
    }

    /**
     * Form edit customer
     */
    public function edit(Customer $customer)
    {
        return view('admin.sales.customer.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'tipe_customer' => 'nullable|in:retail,wholesale,distributor',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $customer->update([
                'nama_customer' => $validated['nama_customer'],
                'email' => $validated['email'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
                'kota' => $validated['kota'] ?? null,
                'provinsi' => $validated['provinsi'] ?? null,
                'kode_pos' => $validated['kode_pos'] ?? null,
                'tipe_customer' => $validated['tipe_customer'] ?? 'retail',
                'status' => $validated['status'] ?? 'active',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('sales.customer.show', $customer->id)
                ->with('success', 'Customer berhasil diupdate.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal update customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus customer
     */
    public function destroy(Customer $customer)
    {
        // Check if customer has orders
        if ($customer->requestOrders()->exists() || $customer->salesOrders()->exists()) {
            return back()->withErrors('Customer tidak dapat dihapus karena memiliki data pesanan/penawaran.');
        }

        DB::beginTransaction();
        try {
            $name = $customer->nama_customer;
            $customer->delete();

            DB::commit();

            return redirect()->route('sales.customer.index')
                ->with('success', "Customer {$name} berhasil dihapus.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menghapus customer: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk autocomplete/select customer
     */
    public function search(Request $request)
    {
        $q = $request->query('q', '');

        $customers = Customer::where('status', 'active')
            ->where(function ($query) use ($q) {
                $query->where('nama_customer', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('telepon', 'like', "%{$q}%");
            })
            ->select('id', 'nama_customer', 'email', 'telepon', 'kota')
            ->limit(20)
            ->get();

        return response()->json($customers);
    }
}
