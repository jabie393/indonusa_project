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
     * Indeks persetujuan quotation kustom oleh Supervisor.
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
                    ->orWhere('our_ref', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('up', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('reason', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('grand_total', 'like', "%{$search}%")
                    ->orWhereDate('date', $search)
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhereDate('created_at', $search)
                    ->orWhere('created_at', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('product_name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('qty', 'like', "%{$search}%")
                            ->orWhere('unit', 'like', "%{$search}%")
                            ->orWhere('price', 'like', "%{$search}%")
                            ->orWhere('subtotal', 'like', "%{$search}%")
                            ->orWhere('discount', 'like', "%{$search}%");
                    })
                    ->orWhereHas('sales', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $quotations = $query->paginate((int) $request->input('perPage', 20))->withQueryString();

        $quotations->getCollection()->transform(function ($item) {
            $item->offer_type = 'custom';
            return $item;
        });

        return view('admin.custom-quotation-approval.index', compact('quotations'));
    }

    /**
     * Supervisor approve/reject a single custom quotation.
     */
    public function approve(Request $request, CustomQuotation $customQuotation)
    {
        $action = $request->input('action');
        if (! in_array($customQuotation->status, ['pending_approval', 'sent', 'rejected_supervisor'])) {
            return back()->withErrors('Quotation tidak dalam status menunggu persetujuan.');
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

            event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));

            return back()->with(['title' => 'Berhasil', 'text' => 'Quotation telah disetujui.']);
        } elseif ($action === 'reject') {
            $validated = $request->validate([
                'reason' => 'required|string|max:2000',
            ]);
            $customQuotation->status = 'rejected_supervisor';
            $customQuotation->reason = $validated['reason'];
            $customQuotation->save();

            event(new \App\Events\RealTimeNotification(
                'Sales',
                $customQuotation->sales_id,
                'custom_quotation_rejected',
                'Quotation Kustom Ditolak!',
                "Quotation Kustom {$customQuotation->quotation_number} ditolak oleh supervisor."
            ));

            return back()->with(['title' => 'Berhasil', 'text' => 'Quotation telah ditolak.']);
        }

        return back()->withErrors('Action tidak valid.');
    }

    /**
     * Bulk Approval for Supervisor on custom quotations.
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
            $quotations = CustomQuotation::whereIn('id', $ids)
                ->where('status', 'pending_approval')
                ->get();

            if ($quotations->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No valid items found for approval/rejection.']);
            }

            foreach ($quotations as $quotation) {
                // Determine if user is allowed
                $userRole = trim(strtolower(Auth::user()->role ?? ''));
                $allowed = array_map('strtolower', ['Supervisor', 'Admin']);
                if (! in_array($userRole, $allowed) && $quotation->sales_id !== Auth::id()) {
                    continue; // Skip unauthorized
                }

                if ($action === 'approve') {
                    $quotation->status = 'approved_supervisor';
                    $quotation->approved_by = Auth::id();
                    $quotation->approved_at = now();
                    $quotation->reason = null;
                } else {
                    $reason = $request->input('reason', 'Bulk rejected by supervisor');
                    $quotation->status = 'rejected_supervisor';
                    $quotation->reason = $reason;
                }
                $quotation->save();

                if ($action === 'reject') {
                    event(new \App\Events\RealTimeNotification(
                        'Sales',
                        $quotation->sales_id,
                        'custom_quotation_rejected',
                        'Quotation Kustom Ditolak!',
                        "Quotation Kustom {$quotation->quotation_number} ditolak oleh supervisor."
                    ));
                }
            }

            DB::commit();

            if ($action === 'approve') {
                event(new \App\Events\RealTimeNotification('All', null, 'refresh_counts'));
            }

            $message = $action === 'approve' ? 'Items approved successfully.' : 'Items rejected successfully.';

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Custom Quotation Bulk Approval Error', ['message' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
        }
    }
}
