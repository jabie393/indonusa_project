<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefectReportController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $query = $request->input('search');

        $goods = Barang::where('status_barang', 'ditinjau_supervisor')
            ->when($query, function ($q) use ($query) {
                return $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate($perPage);

        return view('admin.defect-report.index', compact('goods'));
    }

    public function approve($id)
    {
        $barang = Barang::findOrFail($id);
        
        $barang->status_barang = 'masuk';
        $barang->catatan = 'Disetujui oleh Supervisor: ' . Auth::user()->name;
        $barang->save();

        return redirect()->route('supervisor.defect-report.index')->with([
            'title' => 'Berhasil',
            'text' => 'Laporan defect barang telah disetujui.',
            'icon' => 'success'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $barang = Barang::findOrFail($id);
        
        $barang->status_barang = 'ditolak_supervisor';
        $barang->catatan = 'Ditolak Supervisor: ' . $request->reason;
        $barang->save();

        return redirect()->route('supervisor.defect-report.index')->with([
            'title' => 'Berhasil',
            'text' => 'Laporan defect barang telah ditolak.',
            'icon' => 'success'
        ]);
    }
}
