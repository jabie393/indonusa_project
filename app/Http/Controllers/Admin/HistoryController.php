<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangHistory;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil history barang beserta user yang mengubah
        $histories = BarangHistory::with('user')->latest('changed_at')->paginate(20);

        return view('admin.history.index', compact('histories'));
    }
}
