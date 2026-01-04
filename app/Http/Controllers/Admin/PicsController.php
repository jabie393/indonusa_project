<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pic; // Pastikan model Pic sudah ada
use Illuminate\Support\Facades\Validator;

class PicsController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $query = Pic::query();

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

        return view('admin.pics.index', compact('pics'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Pic::create([
            'name' => $request->name,
            'phone' => $request->telepon,
            'email' => $request->email,
            'position' => $request->position,
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
        ]);

        $pic->update([
            'name' => $request->name,
            'phone' => $request->telepon,
            'email' => $request->email,
            'position' => $request->position,
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
