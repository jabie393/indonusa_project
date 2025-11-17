<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunSalesController extends Controller
{
    public function index()
    {
        $perPage = (int) request('perPage', 10);
        $search = request('search');

        $query = User::where('role', 'Sales');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $salesUsers = $query->latest()->paginate($perPage)->appends(request()->except('page'));
        return view('admin.akun-sales.index', compact('salesUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Sales',
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Akun sales telah berhasil dibuat.']);
        }

        return redirect()->route('akun-sales.index')->with(['title' => 'Berhasil', 'text' => 'Akun sales telah berhasil dibuat.']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('akun-sales.index')->withErrors('Akun sales tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('akun-sales.index')->with(['title' => 'Berhasil', 'text' => 'Akun sales telah berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('akun-sales.index')->withErrors('Akun sales tidak ditemukan.');
        }

        $user->delete();

        return redirect()->route('akun-sales.index')->with(['title' => 'Berhasil', 'text' => 'Akun sales telah berhasil dihapus.']);
    }
}
