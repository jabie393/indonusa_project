<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomQuotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomQuotationApprovalController extends Controller
{
    /**
     * Indeks persetujuan penawaran kustom oleh Supervisor.
     */
    public function index(Request $request)
    {
        $query = CustomQuotation::where('status', 'pending_approval')
            ->with('items', 'sales')
            ->latest();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('sales', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $penawarans = $query->paginate(20);

        $penawarans->getCollection()->transform(function ($item) {
            $item->offer_type = 'custom';
            return $item;
        });

        return view('admin.custom-quotation-approval.index', compact('penawarans'));
    }

    /**
     * Supervisor approve/reject a single custom penawaran.
     */
    public function approve(Request $request, CustomQuotation $customQuotation)
    {
        $action = $request->input('action');
        if (! in_array($customQuotation->status, ['pending_approval', 'sent', 'rejected_supervisor'])) {
            return back()->withErrors('Penawaran tidak dalam status menunggu persetujuan.');
        }
        $userRole = trim(strtolower(Auth::user()->role ?? ''));
        $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
        if ($customQuotation->sales_id !== Auth::id() && ! in_array($userRole, $allowed)) {
            abort(403);
        }
        if ($action === 'approve') {
            $customQuotation->status = 'approved_supervisor';
            $customQuotation->approved_by = Auth::id();
            $customQuotation->approved_at = now();
            $customQuotation->reason = null;
            $customQuotation->save();

            return back()->with(['title' => 'Berhasil', 'text' => 'Penawaran telah disetujui.']);
        } elseif ($action === 'reject') {
            $validated = $request->validate([
                'reason' => 'required|string|max:2000',
            ]);
            $customQuotation->status = 'rejected_supervisor';
            $customQuotation->reason = $validated['reason'];
            $customQuotation->save();

            return back()->with(['title' => 'Berhasil', 'text' => 'Penawaran telah ditolak.']);
        }

        return back()->withErrors('Action tidak valid.');
    }

    /**
     * Bulk Approval for Supervisor on custom penawarans.
     */
    public function bulkApproval(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action'); // 'approve' or 'reject'

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        if (! in_array($action, ['approve', 'reject'])) {
            return response()->json(['success' => false, 'message' => 'Invalid action.']);
        }

        DB::beginTransaction();
        try {
            $penawarans = CustomQuotation::whereIn('id', $ids)
                ->where('status', 'pending_approval')
                ->get();

            if ($penawarans->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No valid items found for approval/rejection.']);
            }

            foreach ($penawarans as $penawaran) {
                // Determine if user is allowed
                $userRole = trim(strtolower(Auth::user()->role ?? ''));
                $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
                if (! in_array($userRole, $allowed) && $penawaran->sales_id !== Auth::id()) {
                    continue; // Skip unauthorized
                }

                if ($action === 'approve') {
                    $penawaran->status = 'approved_supervisor';
                    $penawaran->approved_by = Auth::id();
                    $penawaran->approved_at = now();
                    $penawaran->reason = null;
                } else {
                    $reason = $request->input('reason', 'Bulk rejected by supervisor');
                    $penawaran->status = 'rejected_supervisor';
                    $penawaran->reason = $reason;
                }
                $penawaran->save();
            }

            DB::commit();

            $message = $action === 'approve' ? 'Items approved successfully.' : 'Items rejected successfully.';

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Penawaran Bulk Approval Error', ['message' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
        }
    }
}
