<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProcurementArrivalRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcurementApprovalController extends Controller
{
    /**
     * Tampilkan daftar kedatangan barang pengadaan yang menunggu persetujuan Supervisor.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        $search = $request->input('search');

        $query = ProcurementArrivalRequest::where('status', 'pending_spv')
            ->with([
                'good',
                'procurementOfGoodsItem.procurementOfGoods.generalAffair',
                'procurementOfGoodsItem.procurementOfGoods.order',
                'procurementOfGoodsItem.procurementOfGoods.customQuotation',
            ])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('good', function ($gq) use ($search) {
                    $gq->where('goods_name', 'like', "%{$search}%")
                        ->orWhere('goods_code', 'like', "%{$search}%");
                })->orWhereHas('procurementOfGoodsItem.procurementOfGoods', function ($pq) use ($search) {
                    $pq->where('procurement_number', 'like', "%{$search}%")
                        ->orWhere('vendor_name', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc');

        $requests = $query->paginate($perPage)->withQueryString();

        return view('admin.procurement-approval.index', compact('requests'));
    }

    /**
     * Setujui kedatangan barang pengadaan oleh Supervisor.
     */
    public function approve(Request $request, ProcurementArrivalRequest $receipt)
    {
        if ($receipt->status !== 'pending_spv') {
            return back()->withErrors('Kedatangan barang ini tidak dalam status menunggu persetujuan Supervisor.');
        }

        DB::beginTransaction();
        try {
            $receipt->status = 'pending_warehouse';
            $receipt->spv_id = Auth::id();
            $receipt->spv_approved_at = now();
            $receipt->save();

            DB::commit();

            try {
                event(new \App\Events\RealTimeNotification(
                    'Warehouse',
                    null,
                    'procurement_arrival_submitted',
                    'Barang Masuk Siap Diverifikasi!',
                    'Ada kedatangan barang pengadaan yang telah disetujui Supervisor dan siap diverifikasi fisik oleh Warehouse.'
                ));

                event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));
            } catch (\Throwable $broadCastEx) {
                Log::warning('Broadcast failed in Supervisor approve procurement: ' . $broadCastEx->getMessage());
            }

            return redirect()->back()->with([
                'title' => 'Berhasil Disetujui!',
                'text' => 'Kedatangan barang telah disetujui dan diteruskan ke Warehouse untuk verifikasi fisik.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Supervisor Approve Procurement Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menyetujui kedatangan barang: ' . $e->getMessage());
        }
    }

    /**
     * Tolak kedatangan barang pengadaan oleh Supervisor.
     */
    public function reject(Request $request, ProcurementArrivalRequest $receipt)
    {
        $request->validate([
            'reason' => 'required|string|min:3|max:500',
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($receipt->status !== 'pending_spv') {
            return back()->withErrors('Kedatangan barang ini tidak dalam status menunggu persetujuan Supervisor.');
        }

        DB::beginTransaction();
        try {
            $receipt->status = 'rejected';
            $receipt->spv_id = Auth::id();
            $receipt->spv_approved_at = null;
            $receipt->rejected_by = Auth::id();
            $receipt->rejected_by_role = 'Supervisor';
            $receipt->rejected_at = now();
            $receipt->reject_reason = $request->input('reason');
            $receipt->save();

            DB::commit();

            try {
                event(new \App\Events\RealTimeNotification(
                    'General Affair',
                    null,
                    'procurement_arrival_rejected',
                    'Kedatangan Ditolak Supervisor!',
                    'Kedatangan barang pengadaan ditolak oleh Supervisor: ' . $request->input('reason')
                ));

                event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));
            } catch (\Throwable $broadCastEx) {
                Log::warning('Broadcast failed in Supervisor reject procurement: ' . $broadCastEx->getMessage());
            }

            return redirect()->back()->with([
                'title' => 'Berhasil Ditolak!',
                'text' => 'Kedatangan barang pengadaan berhasil ditolak.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Supervisor Reject Procurement Error: ' . $e->getMessage());
            return back()->withErrors('Gagal menolak kedatangan barang: ' . $e->getMessage());
        }
    }
}
