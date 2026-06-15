<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\CustomQuotation;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;

class QuotationApprovalController extends Controller
{
    /**
     * Display a combined listing of standard and custom quotations pending supervisor approval.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Query for custom quotation (status = 'sent')
        $quotationsQuery = CustomQuotation::where('status', 'sent')
            ->with(['items', 'sales']);

        if ($search) {
            $quotationsQuery->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('up', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('sales', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $quotations = $quotationsQuery->get();

        // Query for Quotations that require supervisor approval
        $requestOrdersQuery = Quotation::whereHas('order', function ($query) {
            $query->where('status', 'sent_to_supervisor');
        })->with(['items', 'sales', 'order', 'customer.pics']);

        if ($search) {
            $requestOrdersQuery->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('pic', function ($picQuery) use ($search) {
                        $picQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('position', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer.pics', function ($picQuery) use ($search) {
                        $picQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('position', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('sales', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }
        $requestOrders = $requestOrdersQuery->get();

        // Tag each item with a type so view can differentiate
        $quotations->each(function ($p) {
            $p->offer_type = 'custom'; });
        $requestOrders->each(function ($r) {
            $r->offer_type = 'request_order'; });

        // Merge and sort by created_at desc
        $all = $quotations->concat($requestOrders)->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = (int) $request->input('perPage', 10);
        $page = (int) $request->input('page', 1);
        $offset = ($page - 1) * $perPage;
        $currentItems = $all->slice($offset, $perPage)->values();

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $all->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.quotation-approval.index', ['quotations' => $paginator]);
    }

    /**
     * Approve regular quotation.
     */
    public function approve(Request $request, Quotation $quotation)
    {
        $order = Order::where('quotation_id', $quotation->id)->first();
        if (!$order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }
        $order->update([
            'status' => 'open',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
        ]);

        event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil di-approve oleh supervisor.']);
    }

    /**
     * Reject regular quotation.
     */
    public function reject(Request $request, Quotation $quotation)
    {
        $request->validate([
            'reason' => 'required|string|min:5|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
            'reason.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $order = Order::where('quotation_id', $quotation->id)->first();
        if (!$order) {
            return back()->with(['title' => 'Gagal!', 'text' => 'Order tidak ditemukan.']);
        }

        $order->update([
            'status' => 'rejected_supervisor',
            'supervisor_id' => Auth::id(),
            'approved_at' => now(),
            'reason' => $request->reason,
        ]);

        $quotation->update(['reason' => $request->reason]);

        event(new \App\Events\RealTimeNotification(
            'Sales',
            $quotation->sales_id,
            'quotation_rejected',
            'Quotation Ditolak!',
            "Quotation {$quotation->quotation_number} ditolak oleh supervisor."
        ));

        return redirect()->back()->with(['title' => 'Berhasil!', 'text' => 'Request order berhasil ditolak.']);
    }

    /**
     * Supervisor log history for both standard and custom quotations.
     */
    public function history(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);

        $query = \App\Models\QuotationHistory::with(['sales', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'LIKE', "%{$search}%")
                    ->orWhere('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('pic_name', 'LIKE', "%{$search}%")
                    ->orWhere('sales_name', 'LIKE', "%{$search}%")
                    ->orWhere('grand_total', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhere('reason', 'LIKE', "%{$search}%")
                    ->orWhereDate('changed_at', $search)
                    ->orWhere('changed_at', 'LIKE', "%{$search}%");

                // Translate status labels
                $normalizedQuery = strtolower($search);
                $statusLabels = [
                    'approved' => ['approved', 'approve', 'disetujui', 'open'],
                    'rejected' => ['rejected', 'reject', 'ditolak'],
                    'deleted' => ['deleted', 'delete', 'hapus', 'dihapus'],
                ];

                foreach ($statusLabels as $status => $labels) {
                    foreach ($labels as $label) {
                        if (str_contains($label, $normalizedQuery)) {
                            if ($status === 'approved') {
                                $q->orWhere('status', 'approved_supervisor')
                                  ->orWhere('status', 'open');
                            } elseif ($status === 'rejected') {
                                $q->orWhere('status', 'rejected_supervisor');
                            } else {
                                $q->orWhere('status', $status);
                            }
                            break;
                        }
                    }
                }
            });
        }

        $histories = $query->latest('changed_at')
            ->paginate($perPage)
            ->appends($request->except('page'));

        $histories->getCollection()->transform(function ($item) {
            return [
                'type' => $item->quotation_type,
                'id' => $item->id,
                'number' => $item->quotation_number,
                'customer' => $item->customer_name,
                'pic' => $item->pic_name ?? '-',
                'sales' => $item->sales_name ?? ($item->sales->name ?? '-'),
                'grand_total' => 'Rp ' . number_format($item->grand_total, 2, ',', '.'),
                'status' => $item->status,
                'reason' => $item->reason ?? '-',
                'approved_at' => $item->changed_at ? $item->changed_at->format('d-m-Y H:i') : '-',
                'raw_date' => $item->changed_at,
            ];
        });

        return view('admin.quotation-history.index', [
            'histories' => $histories,
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

        event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));

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

        $order->loadMissing('quotation');
        event(new \App\Events\RealTimeNotification(
            'Sales',
            $order->sales_id,
            'quotation_rejected',
            'Quotation Ditolak!',
            "Quotation " . ($order->quotation?->quotation_number ?? $order->order_number) . " ditolak oleh supervisor."
        ));

        return redirect()->route('orders.history')->with('success', 'Order ditolak dan dikembalikan ke Admin Sales.');
    }
}
