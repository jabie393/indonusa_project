<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\SystemSetting;
use App\Models\User;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    /**
     * Helper to resolve start and end dates from period parameters
     */
    private function resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput)
    {
        $startDate = null;
        $endDate = null;

        if ($periodeType === 'daily') {
            $d = Carbon::parse($date);
            $startDate = $d->copy()->startOfDay();
            $endDate = $d->copy()->endOfDay();
        } elseif ($periodeType === 'weekly') {
            // Week division: W1 (1-7), W2 (8-14), W3 (15-21), W4 (22-28), W5 (29-end of month)
            $firstDay = Carbon::create($year, $month, 1)->startOfDay();
            if ($week == 1) {
                $startDate = $firstDay->copy();
                $endDate = $firstDay->copy()->addDays(6)->endOfDay();
            } elseif ($week == 2) {
                $startDate = $firstDay->copy()->addDays(7);
                $endDate = $firstDay->copy()->addDays(13)->endOfDay();
            } elseif ($week == 3) {
                $startDate = $firstDay->copy()->addDays(14);
                $endDate = $firstDay->copy()->addDays(20)->endOfDay();
            } elseif ($week == 4) {
                $startDate = $firstDay->copy()->addDays(21);
                $endDate = $firstDay->copy()->addDays(27)->endOfDay();
            } else {
                $startDate = $firstDay->copy()->addDays(28);
                $endDate = $firstDay->copy()->endOfMonth()->endOfDay();
            }
        } elseif ($periodeType === 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        } elseif ($periodeType === 'yearly') {
            $startDate = Carbon::create($year, 1, 1)->startOfDay();
            $endDate = Carbon::create($year, 12, 31)->endOfDay();
        } elseif ($periodeType === 'custom' && $startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            // Default fallback
            $startDate = Carbon::create($year, $month, 1)->startOfDay();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        }

        return [$startDate, $endDate];
    }

    /**
     * Common query builder for both index view and exports
     */
    private function buildReportQuery(Request $request)
    {
        $periodeType = $request->input('periode_type', 'monthly');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', 1);
        $date = $request->input('date', date('Y-m-d'));
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        list($startDate, $endDate) = $this->resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput);

        $salesId = $request->input('sales_id');
        $reportType = $request->input('report_type', 'all');
        $status = $request->input('status', 'all');

        $user = Auth::user();
        $isSales = trim(strtolower($user->role ?? '')) === 'sales';

        if ($isSales) {
            $salesId = $user->id; // Lock to current user if role is sales
        }

        // 1. Standard Quotation Query
        $qQuery = DB::table('quotations')
            ->leftJoin('users', 'quotations.sales_id', '=', 'users.id')
            ->leftJoin('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->select(
                'quotations.id',
                'quotations.created_at',
                'quotations.quotation_number',
                'quotations.customer_name',
                'quotations.subject',
                'quotations.grand_total',
                'users.name as sales_name',
                'quotations.sales_id',
                DB::raw("'Standard Quotation' as type"),
                'orders.status as order_status',
                DB::raw("NULL as direct_status"),
                'quotations.custom_quotation_id'
            );

        // 2. Custom Quotation Query
        $cqQuery = DB::table('custom_quotations')
            ->leftJoin('users', 'custom_quotations.sales_id', '=', 'users.id')
            ->select(
                'custom_quotations.id',
                'custom_quotations.created_at',
                'custom_quotations.quotation_number',
                'custom_quotations.to as customer_name',
                'custom_quotations.subject',
                'custom_quotations.grand_total',
                'users.name as sales_name',
                'custom_quotations.sales_id',
                DB::raw("'Custom Quotation' as type"),
                DB::raw("NULL as order_status"),
                'custom_quotations.status as direct_status',
                'custom_quotations.id as custom_quotation_id'
            );

        // Apply date filter
        if ($startDate && $endDate) {
            $qQuery->whereBetween('quotations.created_at', [$startDate, $endDate]);
            $cqQuery->whereBetween('custom_quotations.created_at', [$startDate, $endDate]);
        }

        // Apply sales filter
        if ($salesId) {
            $qQuery->where('quotations.sales_id', $salesId);
            $cqQuery->where('custom_quotations.sales_id', $salesId);
        }

        // Apply status filter
        if ($status && $status !== 'all') {
            if ($status === 'belum_diproses') {
                $qQuery->whereNull('orders.status');
                $cqQuery->whereRaw('1 = 0');
            } elseif ($status === 'pending') {
                $qQuery->whereIn('orders.status', ['pending', 'sent_to_supervisor']);
                $cqQuery->whereIn('custom_quotations.status', ['draft', 'pending_approval', 'open', 'sent']);
            } elseif ($status === 'approved') {
                $qQuery->where('orders.status', 'open');
                $cqQuery->whereIn('custom_quotations.status', ['approved', 'approved_supervisor']);
            } elseif ($status === 'rejected') {
                $qQuery->whereIn('orders.status', ['rejected_supervisor', 'rejected_warehouse']);
                $cqQuery->whereIn('custom_quotations.status', ['rejected', 'rejected_supervisor']);
            } elseif ($status === 'sent_to_warehouse') {
                $qQuery->where('orders.status', 'sent_to_warehouse');
                $cqQuery->where('custom_quotations.status', 'sent_to_warehouse');
            } elseif ($status === 'completed') {
                $qQuery->where('orders.status', 'completed');
                $cqQuery->where('custom_quotations.status', 'completed');
            } elseif ($status === 'partial') {
                $qQuery->where('orders.status', 'not_completed');
                $cqQuery->whereRaw('1 = 0');
            } elseif ($status === 'expired') {
                $qQuery->whereRaw('1 = 0');
                $cqQuery->where('custom_quotations.status', 'expired');
            }
        }

        // Combine queries based on report type
        if ($reportType === 'quotation') {
            $finalQuery = DB::table(DB::raw("({$qQuery->toSql()}) as combined"))
                ->mergeBindings($qQuery);
        } elseif ($reportType === 'custom_quotation') {
            $finalQuery = DB::table(DB::raw("({$cqQuery->toSql()}) as combined"))
                ->mergeBindings($cqQuery);
        } else {
            $finalQuery = DB::table(DB::raw("({$qQuery->toSql()} UNION {$cqQuery->toSql()}) as combined"))
                ->mergeBindings($qQuery)
                ->mergeBindings($cqQuery);
        }

        return $finalQuery;
    }

    /**
     * Helper to render human-readable status badge styles and labels
     */
    public static function getStatusDetails($type, $orderStatus, $directStatus, $customQuotationId = null)
    {
        $status = '-';
        $label = '-';
        $badgeClass = 'bg-gray-50 text-gray-700 border-gray-200';

        if ($type === 'Standard Quotation') {
            $status = $orderStatus ?: 'belum_diproses';
            
            // Check for Under Procurement status
            if ($orderStatus === 'sent_to_warehouse' && $customQuotationId) {
                $hasActiveProcurement = DB::table('procurement_of_goods')
                    ->where('custom_quotation_id', $customQuotationId)
                    ->where('status', '!=', 'completed')
                    ->exists();
                $noProcurement = !DB::table('procurement_of_goods')
                    ->where('custom_quotation_id', $customQuotationId)
                    ->exists();

                if ($hasActiveProcurement || $noProcurement) {
                    $status = 'under_procurement';
                }
            }

            $labels = [
                'belum_diproses' => 'Belum Diproses',
                'pending' => 'Pending',
                'open' => 'Open',
                'sent_to_supervisor' => 'Waiting for Supervisor Approval',
                'rejected_supervisor' => 'Rejected by Supervisor',
                'sent_to_warehouse' => 'Sent to Warehouse',
                'under_procurement' => 'Under Procurement',
                'approved_warehouse' => 'Approved by Warehouse',
                'rejected_warehouse' => 'Rejected by Warehouse',
                'completed' => 'Completed',
                'not_completed' => 'Partial Delivery',
            ];
            $label = $labels[$status] ?? $status;
        } else {
            $status = $directStatus ?: 'draft';
            $labels = [
                'draft' => 'Draft',
                'pending_approval' => 'Waiting for Supervisor Approval',
                'open' => 'Open',
                'sent_to_warehouse' => 'Sent to Warehouse',
                'sent_to_quotation' => 'Sent to Quotation',
                'approved' => 'Approved / Open',
                'rejected' => 'Rejected',
                'expired' => 'Expired',
                'approved_supervisor' => 'Approved by Supervisor',
                'rejected_supervisor' => 'Rejected by Supervisor',
                'procurement_pending' => 'Procurement Pending',
                'partially_available' => 'Partially Available',
                'ready_for_delivery' => 'Ready for Delivery',
                'completed' => 'Completed',
            ];
            $label = $labels[$status] ?? $status;
        }

        // Classes mapping
        if (in_array($status, ['approved', 'approved_supervisor', 'open', 'approved_warehouse'])) {
            $badgeClass = 'bg-green-50 text-green-700 border-green-200';
        } elseif (in_array($status, ['rejected', 'rejected_supervisor', 'rejected_warehouse'])) {
            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
        } elseif (in_array($status, ['pending', 'pending_approval', 'draft', 'sent_to_supervisor', 'procurement_pending', 'belum_diproses'])) {
            $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
        } elseif (in_array($status, ['sent_to_warehouse', 'sent_to_quotation', 'partially_available', 'under_procurement'])) {
            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
        } elseif ($status === 'ready_for_delivery') {
            $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
        } elseif ($status === 'completed') {
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }

        return [
            'label' => $label,
            'class' => $badgeClass
        ];
    }

    /**
     * Display the sales report page
     */
    public function index(Request $request)
    {
        $salesUsers = User::where('role', 'Sales')->orderBy('name')->get();
        
        $finalQuery = $this->buildReportQuery($request);
        
        // Calculate totals for the filtered results
        $summaryQuery = clone $finalQuery;
        $totalCount = $summaryQuery->count();
        $totalAmount = $summaryQuery->sum('grand_total');

        $search = $request->input('search');
        if ($search) {
            $finalQuery->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('perPage', 10);
        $results = $finalQuery->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('admin.sales-report.index', compact('results', 'salesUsers', 'totalCount', 'totalAmount'));
    }

    /**
     * Export the filtered data to Excel
     */
    public function exportExcel(Request $request)
    {
        $finalQuery = $this->buildReportQuery($request);
        
        $search = $request->input('search');
        if ($search) {
            $finalQuery->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $results = $finalQuery->orderBy('created_at', 'desc')->get();

        // Get active filters descriptive string for header
        $filterDescription = $this->getFilterDescription($request);

        $data = [
            'results' => $results,
            'filter_description' => $filterDescription,
            'company_name' => SystemSetting::get('company_name', 'PT. INDONUSA JAYA BERSAMA'),
            'company_address' => SystemSetting::get('company_address', 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296'),
            'company_phone' => SystemSetting::get('company_phone', '08121634173'),
            'company_email' => SystemSetting::get('company_email', 'info@indonusa.com'),
            'leader_name' => SystemSetting::get('leader_name', 'Alimul Imam S.AP'),
            'leader_position' => SystemSetting::get('leader_position', 'Direktur'),
        ];

        return Excel::download(new SalesReportExport($data), 'Laporan-Sales-' . now()->format('YmdHis') . '.xlsx');
    }

    /**
     * Export the filtered data to PDF
     */
    public function exportPdf(Request $request)
    {
        $finalQuery = $this->buildReportQuery($request);
        
        $search = $request->input('search');
        if ($search) {
            $finalQuery->where(function ($q) use ($search) {
                $q->where('quotation_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('sales_name', 'like', "%{$search}%");
            });
        }

        $results = $finalQuery->orderBy('created_at', 'desc')->get();
        $filterDescription = $this->getFilterDescription($request);

        $data = [
            'results' => $results,
            'filter_description' => $filterDescription,
            'company_name' => SystemSetting::get('company_name', 'PT. INDONUSA JAYA BERSAMA'),
            'company_address' => SystemSetting::get('company_address', 'Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296'),
            'company_phone' => SystemSetting::get('company_phone', '08121634173'),
            'company_email' => SystemSetting::get('company_email', 'info@indonusa.com'),
            'leader_name' => SystemSetting::get('leader_name', 'Alimul Imam S.AP'),
            'leader_position' => SystemSetting::get('leader_position', 'Direktur'),
            'print_date' => now()->format('d M Y H:i:s'),
        ];

        $html = view('admin.pdf.sales-report-pdf', $data)->render();

        $pdf = $this->getBrowsershot($html)
            ->format('A4')
            ->margins(12.7, 12.7, 12.7, 12.7) // 1.27 cm margins
            ->showBackground()
            ->writeOptionsToFile()
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Laporan-Sales-' . now()->format('YmdHis') . '.pdf"');
    }

    /**
     * Generate dynamic string representing the selected filters
     */
    private function getFilterDescription(Request $request)
    {
        $periodeType = $request->input('periode_type', 'monthly');
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        $week = $request->input('week', 1);
        $date = $request->input('date', date('Y-m-d'));
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        list($startDate, $endDate) = $this->resolveDateRange($periodeType, $year, $month, $week, $date, $startDateInput, $endDateInput);

        $typeLabel = 'Semua Tipe';
        if ($request->input('report_type') === 'quotation') {
            $typeLabel = 'Standard Quotation Only';
        } elseif ($request->input('report_type') === 'custom_quotation') {
            $typeLabel = 'Custom Quotation Only';
        }

        $salesLabel = 'Semua Sales';
        if ($request->filled('sales_id')) {
            $sales = User::find($request->input('sales_id'));
            if ($sales) {
                $salesLabel = 'Sales: ' . $sales->name;
            }
        }

        $statusLabel = 'Semua Status';
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $statusMapping = [
                'belum_diproses' => 'Belum Diproses',
                'pending' => 'Pending/Waiting Approval',
                'approved' => 'Approved/Open',
                'rejected' => 'Rejected',
                'sent_to_warehouse' => 'Sent to Warehouse',
                'completed' => 'Completed',
                'partial' => 'Partial Delivery',
                'expired' => 'Expired'
            ];
            $statusLabel = 'Status: ' . ($statusMapping[$request->input('status')] ?? $request->input('status'));
        }

        $dateDesc = '';
        if ($periodeType === 'daily') {
            $dateDesc = 'Harian (' . Carbon::parse($date)->format('d F Y') . ')';
        } elseif ($periodeType === 'weekly') {
            $monthsArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $dateDesc = "Mingguan (Minggu ke-{$week} {$monthsArr[$month]} {$year})";
        } elseif ($periodeType === 'monthly') {
            $monthsArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $dateDesc = "Bulanan ({$monthsArr[$month]} {$year})";
        } elseif ($periodeType === 'yearly') {
            $dateDesc = "Tahunan ({$year})";
        } elseif ($periodeType === 'custom') {
            $dateDesc = "Rentang Kustom (" . Carbon::parse($startDateInput)->format('d/m/Y') . ' s/d ' . Carbon::parse($endDateInput)->format('d/m/Y') . ')';
        }

        return "Periode: {$dateDesc} | Tipe: {$typeLabel} | {$salesLabel} | {$statusLabel}";
    }
}
