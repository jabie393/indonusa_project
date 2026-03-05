<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    /**
     * Show all approval histories (sent penawaran, custom penawaran, defect report) for supervisor.
     */
    public function history(Request $request)
    {
        $search = $request->input('search');

        // Request Orders (sent penawaran)
        $roQuery = \App\Models\RequestOrder::with(['order', 'sales'])
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['approved_supervisor', 'rejected_supervisor']);
            });

        if ($search) {
            $roQuery->where(function($q) use ($search) {
                $q->where('nomor_penawaran', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('sales', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $requestOrders = $roQuery->get()->map(function($ro) {
            return [
                'type' => 'request_order',
                'id' => $ro->id,
                'number' => $ro->nomor_penawaran,
                'customer' => $ro->customer_name,
                'sales' => $ro->sales->name ?? '-',
                'grand_total' => 'Rp ' . number_format($ro->grand_total, 2, ',', '.'),
                'status' => $ro->order->status ?? '-',
                'reason' => $ro->order->reason ?? '-',
                'approved_at' => $ro->order->approved_at ? $ro->order->approved_at->format('d-m-Y H:i') : '-',
                'raw_date' => $ro->order->approved_at,
            ];
        });

        // Custom Penawaran
        $cpQuery = \App\Models\CustomPenawaran::with(['sales', 'order'])
            ->whereIn('status', ['approved_supervisor', 'rejected_supervisor']);

        if ($search) {
            $cpQuery->where(function($q) use ($search) {
                $q->where('penawaran_number', 'LIKE', "%{$search}%")
                  ->orWhere('to', 'LIKE', "%{$search}%")
                  ->orWhereHas('sales', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $customPenawarans = $cpQuery->get()->map(function($cp) {
            return [
                'type' => 'custom_penawaran',
                'id' => $cp->id,
                'number' => $cp->penawaran_number,
                'customer' => $cp->to,
                'sales' => $cp->sales->name ?? '-',
                'grand_total' => 'Rp ' . number_format($cp->grand_total, 2, ',', '.'),
                'status' => $cp->status,
                'reason' => $cp->reason ?? '-',
                'approved_at' => $cp->approved_at ? $cp->approved_at->format('d-m-Y H:i') : '-',
                'raw_date' => $cp->approved_at,
            ];
        });

        // Defect Report (BarangHistory, status change by supervisor)
        $drQuery = \App\Models\BarangHistory::with(['barang', 'user'])
            ->whereIn('new_status', ['masuk', 'ditolak_supervisor'])
            ->whereHas('user', function($q) {
                $q->where('role', 'Supervisor');
            });

        if ($search) {
            $drQuery->where(function($q) use ($search) {
                $q->where('kode_barang', 'LIKE', "%{$search}%")
                  ->orWhere('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($sq) use ($search) {
                      $sq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $defectReports = $drQuery->get()->map(function($bh) {
            return [
                'type' => 'defect_report',
                'id' => $bh->barang_id,
                'number' => $bh->kode_barang,
                'customer' => $bh->nama_barang ?? '-',
                'sales' => $bh->user->name ?? '-',
                'grand_total' => '-',
                'status' => $bh->new_status === 'masuk' ? 'approved' : ($bh->new_status === 'ditolak_supervisor' ? 'rejected' : $bh->new_status),
                'reason' => $bh->note ?? '-',
                'approved_at' => $bh->changed_at ? $bh->changed_at->format('d-m-Y H:i') : '-',
                'raw_date' => $bh->changed_at,
            ];
        });

        // Gabungkan dan urutkan berdasarkan tanggal terbaru
        $all = $requestOrders->concat($customPenawarans)->concat($defectReports)->sortByDesc(function($item) {
            return $item['raw_date'] ? (string) $item['raw_date'] : '';
        })->values();

        // Paginate manually
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $all->slice($offset, $perPage),
            $all->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('supervisor.history', [
            'histories' => $paginated,
        ]);
    }

    // Approve penawaran
    public function approve($id)
    {
        $order = Order::where('request_order_id', $id)->firstOrFail();
        $order->update([
            'status'        => 'approved_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at'   => now(),
        ]);
        return redirect()->back()->with('success', 'Penawaran berhasil disetujui');
    }

    // Reject penawaran
    public function supervisorReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $order = Order::where('request_order_id', $id)->first();
        if (!$order) {
            return back()->withErrors('Order tidak ditemukan.');
        }

        $order->update([
            'status'        => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at'   => now(),
            'reason'        => $request->reason,
        ]);

        // Simpan reason ke request_orders table agar sales bisa lihat di halaman show
        $requestOrder = \App\Models\RequestOrder::find($id);
        if ($requestOrder) {
            $requestOrder->update(['reason' => $request->reason]);
        }

        return redirect()->back()->with('success', 'Request order berhasil ditolak.');
    }
}
