<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangHistory;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil history barang beserta user yang mengubah
        // Support search and per-page pagination
        $perPage = request()->input('perPage', 10);
        $query = request()->input('search');

        $histories = BarangHistory::with('user')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('kode_barang', 'like', "%{$query}%")
                        ->orWhere('nama_barang', 'like', "%{$query}%")
                        ->orWhere('kategori', 'like', "%{$query}%")
                        ->orWhere('note', 'like', "%{$query}%");
                })
                    ->orWhereHas('user', function ($u) use ($query) {
                        // 'display_name' is an accessor, not a DB column — search 'name' instead
                        $u->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    });
            })
            ->latest('changed_at')
            ->paginate($perPage)
            ->appends(request()->except('page'));

        return view('admin.history.index', compact('histories'));
    }
}
