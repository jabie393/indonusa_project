<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CustomPenawaran;
use App\Models\RequestOrder;
use Illuminate\Support\Facades\Auth;

class QuotationApprovalController extends Controller
{
    /**
     * Display a combined listing of standard and custom quotations pending supervisor approval.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Query for custom penawaran (status = 'sent')
        $penawaransQuery = CustomPenawaran::where('status', 'sent')
            ->with(['items', 'sales']);

        if ($search) {
            $penawaransQuery->where(function($q) use ($search) {
                $q->where('penawaran_number', 'like', "%{$search}%")
                  ->orWhere('to', 'like', "%{$search}%")
                  ->orWhereHas('sales', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $penawarans = $penawaransQuery->get();

        // Query for RequestOrders that require supervisor approval
        $requestOrdersQuery = RequestOrder::whereHas('order', function($query) {
            $query->where('status', 'sent_to_supervisor');
        })->with(['items', 'sales', 'order', 'customer.pics']);

        if ($search) {
            $requestOrdersQuery->where(function($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhereHas('sales', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
        $requestOrders = $requestOrdersQuery->get();

        // Tag each item with a type so view can differentiate
        $penawarans->each(function($p) { $p->offer_type = 'custom'; });
        $requestOrders->each(function($r) { $r->offer_type = 'request_order'; });

        // Merge and sort by created_at desc
        $all = $penawarans->concat($requestOrders)->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = 10;
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $currentItems = $all->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.quotation-approval.index', ['penawarans' => $paginator]);
    }

    /**
     * Approve regular quotation.
     */
    public function approve(Request $request, RequestOrder $quotation)
    {
        $order = Order::where('request_order_id', $quotation->id)->first();
        if (! $order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }
        $order->update([
            'status' => 'approved_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil di-approve oleh supervisor.']);
    }

    /**
     * Reject regular quotation.
     */
    public function reject(Request $request, RequestOrder $quotation)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $order = Order::where('request_order_id', $quotation->id)->first();
        if (! $order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }

        $order->update([
            'status' => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
            'reason' => $request->reason,
        ]);

        $quotation->update(['reason' => $request->reason]);

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil ditolak.']);
    }

    /**
     * Supervisor log history for both standard and custom quotations.
     */
    public function history(Request $request)
    {
        $search = $request->input('search');

        // Request Orders (sent penawaran)
        $roQuery = RequestOrder::with(['order', 'sales'])
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
        $cpQuery = CustomPenawaran::with(['sales', 'order'])
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

        // Gabungkan dan urutkan berdasarkan tanggal terbaru
        $all = $requestOrders->concat($customPenawarans)->sortByDesc(function($item) {
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

    /**
     * incomingShow, incomingApprove, incomingReject, incomingHistory:
     * Supervisor warehouse order actions (logic from legacy AdminPTController).
     */
    public function incomingShow($id)
    {
        return redirect()->route('orders.history')->with('info', 'Halaman detail Incoming Orders telah dihapus.');
    }

    public function incomingApprove($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'sent_to_warehouse'; // diteruskan ke warehouse sesuai flowchart
        $order->supervisor_id = Auth::id();
        $order->save();

        return redirect()->route('admin.quotation_approval')->with('success', 'Order disetujui dan diteruskan ke Admin Warehouse.');
    }

    public function incomingReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        $order->status = 'rejected_supervisor';
        $order->supervisor_id = Auth::id();
        $order->reason = $request->reason;
        $order->save();

        return redirect()->route('orders.history')->with('success', 'Order ditolak dan dikembalikan ke Admin Sales.');
    }

    public function incomingHistory()
    {
        $orders = Order::where('status', '!=', 'pending')->with(['items.barang','sales','supervisor','warehouse'])->latest()->paginate(10);
        return view('admin.incoming-orders.history', compact('orders'));
    }
}
