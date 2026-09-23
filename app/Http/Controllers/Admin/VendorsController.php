<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorsController extends Controller
{
    /**
     * Pastikan hanya role General Affair yang dapat mengelola vendor.
     */
    private function checkRole()
    {
        if (!auth()->check() || auth()->user()->role !== 'General Affair') {
            abort(403, 'Akses ditolak. Hanya General Affair yang dapat mengelola data Vendor.');
        }
    }

    /**
     * Tampilkan daftar vendor.
     */
    public function index(Request $request)
    {
        $this->checkRole();

        $perPage = (int) $request->input('perPage', 10);
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = Vendor::query();

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('vendor_name', 'like', "%{$search}%")
                  ->orWhere('vendor_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('pic_phone', 'like', "%{$search}%")
                  ->orWhere('bank_name', 'like', "%{$search}%")
                  ->orWhere('bank_account_number', 'like', "%{$search}%");
            });
        }

        $vendors = $query->latest()->paginate($perPage);
        $vendors->appends($request->all());

        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Simpan vendor baru.
     */
    public function store(Request $request)
    {
        $this->checkRole();

        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:50',
            'tax_status' => 'required|string|in:PKP,Non PKP',
            'vendor_code' => 'nullable|string|max:50|unique:vendors,vendor_code',
            'npwp' => [$request->input('tax_status') === 'PKP' ? 'required' : 'nullable', 'string', 'max:50'],
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'pic_email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'term_of_payment' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ], [
            'vendor_name.required' => 'Nama vendor / perusahaan wajib diisi.',
            'company_type.required' => 'Bentuk entitas wajib dipilih.',
            'tax_status.required' => 'Status pajak (PKP / Non PKP) wajib dipilih.',
            'tax_status.in' => 'Status pajak harus berupa PKP atau Non PKP.',
            'npwp.required' => 'Nomor NPWP wajib diisi jika vendor berstatus PKP.',
            'phone.required' => 'Nomor telepon / WhatsApp wajib diisi.',
            'address.required' => 'Alamat kantor / gudang wajib diisi.',
            'vendor_code.unique' => 'Kode vendor ini sudah digunakan.',
            'email.email' => 'Format email vendor tidak valid.',
            'pic_email.email' => 'Format email PIC tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $validated['status'] = $request->input('status', 'active') ?: 'active';
            if ($validated['tax_status'] === 'Non PKP') {
                $validated['npwp'] = null;
            }
            $vendor = Vendor::create($validated);

            DB::commit();

            return redirect()->route('vendors.index')->with([
                'title' => 'Berhasil!',
                'text' => "Vendor {$vendor->vendor_name} berhasil ditambahkan.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Store Vendor Error: ' . $e->getMessage());
            return back()->withInput()->withErrors('Gagal menambahkan vendor: ' . $e->getMessage());
        }
    }

    /**
     * Update data vendor.
     */
    public function update(Request $request, $id)
    {
        $this->checkRole();

        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'company_type' => 'required|string|max:50',
            'tax_status' => 'required|string|in:PKP,Non PKP',
            'vendor_code' => 'nullable|string|max:50|unique:vendors,vendor_code,' . $vendor->id,
            'npwp' => [$request->input('tax_status') === 'PKP' ? 'required' : 'nullable', 'string', 'max:50'],
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'pic_email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'term_of_payment' => 'nullable|integer|min:0',
            'status' => 'nullable|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ], [
            'vendor_name.required' => 'Nama vendor / perusahaan wajib diisi.',
            'company_type.required' => 'Bentuk entitas wajib dipilih.',
            'tax_status.required' => 'Status pajak (PKP / Non PKP) wajib dipilih.',
            'tax_status.in' => 'Status pajak harus berupa PKP atau Non PKP.',
            'npwp.required' => 'Nomor NPWP wajib diisi jika vendor berstatus PKP.',
            'phone.required' => 'Nomor telepon / WhatsApp wajib diisi.',
            'address.required' => 'Alamat kantor / gudang wajib diisi.',
            'vendor_code.unique' => 'Kode vendor ini sudah digunakan.',
            'email.email' => 'Format email vendor tidak valid.',
            'pic_email.email' => 'Format email PIC tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $validated['status'] = $request->input('status', $vendor->status) ?: 'active';
            if ($validated['tax_status'] === 'Non PKP') {
                $validated['npwp'] = null;
            }
            $vendor->update($validated);

            DB::commit();

            return redirect()->route('vendors.index')->with([
                'title' => 'Berhasil!',
                'text' => "Data vendor {$vendor->vendor_name} berhasil diperbarui.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update Vendor Error: ' . $e->getMessage());
            return back()->withInput()->withErrors('Gagal memperbarui vendor: ' . $e->getMessage());
        }
    }

    /**
     * Hapus data vendor.
     */
    public function destroy($id)
    {
        $this->checkRole();

        $vendor = Vendor::findOrFail($id);

        if ($vendor->procurements()->exists()) {
            return back()->withErrors("Vendor {$vendor->vendor_name} tidak dapat dihapus karena sudah memiliki riwayat pengadaan. Anda dapat menonaktifkan statusnya.");
        }

        DB::beginTransaction();
        try {
            $vendorName = $vendor->vendor_name;
            $vendor->delete();

            DB::commit();

            return redirect()->route('vendors.index')->with([
                'title' => 'Berhasil!',
                'text' => "Vendor {$vendorName} berhasil dihapus.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delete Vendor Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menghapus vendor: ' . $e->getMessage());
        }
    }

    /**
     * Update status vendor (active / inactive).
     */
    public function updateStatus(Request $request, $id)
    {
        $this->checkRole();

        $vendor = Vendor::findOrFail($id);
        $newStatus = $vendor->status === 'active' ? 'inactive' : 'active';
        $vendor->status = $newStatus;
        $vendor->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => "Status vendor diubah menjadi {$newStatus}.",
            ]);
        }

        return redirect()->back()->with([
            'title' => 'Status Diperbarui',
            'text' => "Status vendor {$vendor->vendor_name} telah diubah menjadi " . ucfirst($newStatus) . '.',
        ]);
    }
}
