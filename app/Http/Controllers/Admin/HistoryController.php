<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsHistory;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil history barang beserta user yang mengubah
        // Support search and per-page pagination
        $perPage = request()->input('perPage', 10);
        $query = request()->input('search');

        $histories = GoodsHistory::with('user')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('goods_code', 'like', "%{$query}%")
                        ->orWhere('goods_name', 'like', "%{$query}%")
                        ->orWhere('category', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('stock', 'like', "%{$query}%")
                        ->orWhere('old_status', 'like', "%{$query}%")
                        ->orWhere('new_status', 'like', "%{$query}%")
                        ->orWhere('action', 'like', "%{$query}%")
                        ->orWhere('note', 'like', "%{$query}%")
                        ->orWhereDate('changed_at', $query)
                        ->orWhere('changed_at', 'like', "%{$query}%")
                        ->orWhereDate('created_at', $query)
                        ->orWhere('created_at', 'like', "%{$query}%");

                    $normalizedQuery = strtolower($query);
                    $statusLabels = [
                        'pending' => ['pending'],
                        'approved' => ['approved', 'approve'],
                        'rejected' => ['rejected', 'reject'],
                        'deleted' => ['deleted', 'delete', 'hapus', 'dihapus'],
                        'out' => ['out', 'keluar'],
                    ];

                    foreach ($statusLabels as $status => $labels) {
                        foreach ($labels as $label) {
                            if (str_contains($label, $normalizedQuery)) {
                                $sub->orWhere('old_status', $status)
                                    ->orWhere('new_status', $status);
                                break;
                            }
                        }
                    }
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
