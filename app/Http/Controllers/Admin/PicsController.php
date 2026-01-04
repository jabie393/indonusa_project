<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pic; // Pastikan model Pic sudah ada
use Illuminate\Support\Facades\Validator;

use App\Models\Customer;

class PicsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Pic::with('customer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $pics = $query->paginate($perPage);
        $pics->appends(['search' => $search, 'perPage' => $perPage]);


        // Ambil data customer untuk dropdown
        $customers = Customer::all();

        return view('admin.pics.index', compact('pics', 'customers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Pic::create([
            'name' => $request->name,
            'phone' => $request->telepon,
            'email' => $request->email,
            'position' => $request->position,
            'customer_id' => $request->customer_id,
        ]);

        return redirect()->route('pics.index')->with(['title' => 'Berhasil', 'text' => 'PIC berhasil ditambahkan.']);
    }

    public function update(Request $request, $id)
    {
        $pic = Pic::find($id);

        if (!$pic) {
            return redirect()->route('pics.index')->withErrors('PIC tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $pic->update([
            'name' => $request->name,
            'phone' => $request->telepon,
            'email' => $request->email,
            'position' => $request->position,
            'customer_id' => $request->customer_id,
        ]);

        return redirect()->route('pics.index')->with(['title' => 'Berhasil', 'text' => 'PIC berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        // Cari data PIC berdasarkan ID
        $pic = Pic::find($id);

        // Jika data tidak ditemukan, kembalikan dengan pesan error
        if (!$pic) {
            return redirect()->route('pics.index')->withErrors('PIC tidak ditemukan.');
        }

        // Hapus data PIC
        $pic->delete();

        // Kembalikan dengan pesan sukses
        return redirect()->route('pics.index')->with(['title' => 'Berhasil', 'text' => 'PIC berhasil dihapus.']);
    }
}
