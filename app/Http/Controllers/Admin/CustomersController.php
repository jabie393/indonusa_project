<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Pic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomersController extends Controller
{
    private function checkRole()
    {
        if (auth()->check() && auth()->user()->role === 'Sales') {
            abort(403, 'Akses ditolak. Peran Sales tidak diizinkan mengakses halaman Customers.');
        }
    }

    public function index(Request $request)
    {
        $this->checkRole();
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Customer::query();

        if (Schema::hasTable('customer_pics')) {
            $query->with('pics', 'users');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('billing_address', 'like', "%{$search}%")
                  ->orWhere('shipping_address', 'like', "%{$search}%")
                  ->orWhere('customer_type', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhereHas('pics', function ($picQuery) use ($search) {
                      $picQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('position', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Ensure pics are loaded
        $query->with('pics');

        $customers = $query->paginate($perPage);
        $customers->appends(['search' => $search, 'perPage' => $perPage]);

        $salesUsers = User::where('role', 'Sales')->get(); // Mengambil data user dengan role Sales
        
        $pics = []; 

        return view('admin.customers.index', compact('customers', 'salesUsers', 'pics'));
    }

    public function store(Request $request)
    {
        $this->checkRole();
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
            'status' => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            // Map 1/0 from checkbox to active/inactive for database enum
            $status = $request->input('status') == '1' ? 'active' : 'inactive';

            $typeInput = strtolower($validatedData['tipe_customer']);
            $customerType = match ($typeInput) {
                'gov' => 'GOV',
                'bumn' => 'BUMN',
                default => ucfirst($typeInput),
            };

            // Buat customer baru
            $customer = Customer::create([
                'customer_name' => $validatedData['name'],
                'npwp' => $validatedData['npwp'] ?? null,
                'term_of_payments' => $validatedData['term_of_payments'] ?? null,
                'credit_limit' => $validatedData['kredit_limit'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['telepon'] ?? null,
                'billing_address' => $validatedData['alamat_penagihan'] ?? null,
                'shipping_address' => $validatedData['alamat_pengiriman'] ?? null,
                'city' => $validatedData['kota'] ?? null,
                'province' => $validatedData['provinsi'] ?? null,
                'postal_code' => $validatedData['kode_pos'] ?? null,
                'customer_type' => $customerType,
                'created_by' => auth()->id(),
                'status' => $status,
            ]);

            // Proses setiap PIC yang dikirim
            foreach ($validatedData['pics'] as $picData) {
                $name = $picData['name'] ?? null;
                
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
                        'customer_name' => $customer->customer_name,
                        'nama_customer' => $customer->customer_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                        'telepon' => $customer->phone,
                        'city' => $customer->city,
                        'kota' => $customer->city,
                    ],
                ]);
            }
            
            return redirect()->route('customers.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil ditambahkan.']);
            
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
        $this->checkRole();
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
            'pics.*.id' => 'nullable|exists:pics,id',
            'pics.*.name' => 'required|string',
            'pics.*.phone' => 'required|string',
            'pics.*.email' => 'nullable|email',
            'pics.*.position' => 'nullable|string',
            'tipe_customer' => 'required|string|in:pribadi,gov,bumn,swasta',
            'status' => 'nullable|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($validatedData['id']);

            // Map 1/0 from checkbox to active/inactive for database enum
            if (auth()->user()->role === 'Supervisor') {
                $status = $request->input('status') == '1' ? 'active' : 'inactive';
            } else {
                $status = $customer->status;
            }

            $typeInput = strtolower($validatedData['tipe_customer']);
            $customerType = match ($typeInput) {
                'gov' => 'GOV',
                'bumn' => 'BUMN',
                default => ucfirst($typeInput),
            };

            $customer->update([
                'customer_name' => $validatedData['name'],
                'npwp' => $validatedData['npwp'] ?? null,
                'term_of_payments' => $validatedData['term_of_payments'] ?? null,
                'credit_limit' => $validatedData['kredit_limit'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['telepon'] ?? null,
                'billing_address' => $validatedData['alamat_penagihan'] ?? null,
                'shipping_address' => $validatedData['alamat_pengiriman'] ?? null,
                'city' => $validatedData['kota'] ?? null,
                'province' => $validatedData['provinsi'] ?? null,
                'postal_code' => $validatedData['kode_pos'] ?? null,
                'customer_type' => $customerType,
                'status' => $status,
            ]);

            // Logic Sinkronisasi PIC One-to-Many
            $submittedPicIds = [];
            
            if (!empty($validatedData['pics'])) {
                foreach ($validatedData['pics'] as $picData) {
                    if (is_string($picData)) {
                        $decoded = json_decode($picData, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $picData = $decoded;
                        } else {
                            $newPic = Pic::create([
                                'customer_id' => $customer->id,
                                'name' => $picData
                            ]);
                            $submittedPicIds[] = $newPic->id;
                            continue;
                        }
                    }

                    if (isset($picData['id']) && $picData['id']) {
                        $existingPic = Pic::where('id', $picData['id'])->where('customer_id', $customer->id)->first();
                        
                        if ($existingPic) {
                            $existingPic->update([
                                'name' => $picData['name'] ?? $existingPic->name,
                                'phone' => $picData['phone'] ?? $existingPic->phone,
                                'email' => $picData['email'] ?? $existingPic->email,
                                'position' => $picData['position'] ?? $existingPic->position,
                            ]);
                            $submittedPicIds[] = $existingPic->id;
                        } else {
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
                        $newPic = Pic::create([
                            'customer_id' => $customer->id,
                            'name' => $picData['name'] ?? ($picData['text'] ?? ($picData['id'] ?? 'Unknown')),
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
            return redirect()->route('customers.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil diperbarui.']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal memperbarui customer: ' . $e->getMessage())->withInput();
        }
    }

    public function getPics($id)
    {
        $this->checkRole();
        $pics = Pic::where('customer_id', $id)->get();
        return response()->json($pics);
    }

    public function updateStatus(Request $request, $id)
    {
        $this->checkRole();
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $customer->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Status customer berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $this->checkRole();

        DB::beginTransaction();
        try {
            $customer = Customer::findOrFail($id);
            
            // Delete associated pics
            $customer->pics()->delete();
            
            $customer->delete();

            DB::commit();

            return redirect()->route('customers.index')->with(['title' => 'Berhasil', 'text' => 'Customer berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal menghapus customer: ' . $e->getMessage());
        }
    }
}
