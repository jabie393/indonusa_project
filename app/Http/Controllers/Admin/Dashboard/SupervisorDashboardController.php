<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesPerformanceExport;
use App\Exports\QuotationsReportExport;
use Illuminate\Support\Facades\DB;

class SupervisorDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Handle filters
        $threshold = (int) $request->query('threshold', 20);
        $dateStartRaw = $request->input('date_start');
        $dateEndRaw = $request->input('date_end');

        $dateStart = null;
        $dateEnd = null;

        if ($dateStartRaw && strtotime($dateStartRaw)) {
            $dateStart = \Carbon\Carbon::parse($dateStartRaw)->startOfDay();
        }
        if ($dateEndRaw && strtotime($dateEndRaw)) {
            $dateEnd = \Carbon\Carbon::parse($dateEndRaw)->endOfDay();
        }

        // 2. Calculate Stats Range
        $start = $dateStart ? (clone $dateStart) : \Carbon\Carbon::now()->startOfMonth();
        $end = $dateEnd ? (clone $dateEnd) : \Carbon\Carbon::now()->endOfMonth();

        // Ensure start is before end
        if ($dateStart && $dateEnd && $start->gt($end)) {
            $temp = clone $start;
            $start = clone $end->startOfDay();
            $end = clone $temp->endOfDay();
        }

        // Comparison range (Last month or previous equivalent slice)
        if ($dateStart && $dateEnd) {
            $diffInDays = $start->diffInDays($end) + 1;
            $compStart = (clone $start)->subDays($diffInDays);
            $compEnd = (clone $end)->subDays($diffInDays);
        } else {
            $compStart = \Carbon\Carbon::now()->subMonth()->startOfMonth();
            $compEnd = \Carbon\Carbon::now()->subMonth()->endOfMonth();
        }

        // A. Waiting Approval (RequestOrder + CustomPenawaran pending supervisor)
        // Note: Pending is usually current state, but we filter by creation date if requested
        $pendingROQuery = \App\Models\RequestOrder::whereHas('order', function($q) {
            $q->where('status', 'sent_to_supervisor');
        });
        $pendingCPQuery = \App\Models\CustomPenawaran::where('status', 'pending_approval');
        
        $totalPending = (clone $pendingROQuery)->whereBetween('created_at', [$start, $end])->count() 
            + (clone $pendingCPQuery)->whereBetween('created_at', [$start, $end])->count();
        
        $lastMonthPending = (clone $pendingROQuery)->whereBetween('created_at', [$compStart, $compEnd])->count()
            + (clone $pendingCPQuery)->whereBetween('created_at', [$compStart, $compEnd])->count();

        // B. Total Approved (Order approved)
        $approvedQuery = \App\Models\Order::whereIn('status', ['approved_warehouse', 'completed']);
        $totalApproved = (clone $approvedQuery)->whereBetween('created_at', [$start, $end])->count();
        $lastMonthApproved = (clone $approvedQuery)->whereBetween('created_at', [$compStart, $compEnd])->count();

        // C. Revenue (Sum subtotal from RequestOrder where Order is completed/approved)
        $revenueQuery = \App\Models\RequestOrder::whereHas('order', function($q) {
            $q->whereIn('status', ['approved_warehouse', 'completed']);
        });
        $totalRevenue = (clone $revenueQuery)->whereBetween('created_at', [$start, $end])->sum('subtotal');
        $lastMonthRevenue = (clone $revenueQuery)->whereBetween('created_at', [$compStart, $compEnd])->sum('subtotal');

        // D. Sales Performance (Approved vs Total)
        $perfQuery = \App\Models\Order::whereIn('status', ['completed']);
        $salesPerformance = (clone $perfQuery)->whereBetween('created_at', [$start, $end])->count();
        $lastMonthPerf = (clone $perfQuery)->whereBetween('created_at', [$compStart, $compEnd])->count();

        // 3. Chart Data (IMC - Sales Trend across all sales)
        $selectedYear = (int) $request->query('year', now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $imcMasuk = []; // Requests
        $imcKeluar = []; // Completed
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = (float) \App\Models\RequestOrder::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');

            $imcKeluar[] = (float) \App\Models\RequestOrder::whereHas('order', function($q) {
                    $q->whereIn('status', ['approved_warehouse', 'completed']);
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');
        }

        $imcYears = \App\Models\RequestOrder::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($imcYears)) $imcYears = [now()->year];

        // 4. Chart Data (SVC - Order Status Distribution)
        // We'll count orders by status for the pie chart
        $statusCounts = \App\Models\Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $svcLabels = $statusCounts->pluck('status')->map(fn($s) => ucwords(str_replace('_', ' ', $s)))->toArray();
        $svcData = $statusCounts->pluck('total')->toArray();

        // 5. Pending Orders Table (respect date filter)
        $pendingOrders = \App\Models\RequestOrder::with(['sales', 'customer'])
            ->whereHas('order', function($q) {
                $q->where('status', 'sent_to_supervisor');
            })
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->take(10)
            ->get();

        // 6. Sales Performance Table (respect date filter)
        $salesList = \App\Models\User::where('role', 'Sales')->get();
        $salesPerfData = [];
        foreach ($salesList as $s) {
            $total = \App\Models\RequestOrder::where('sales_id', $s->id)
                ->whereBetween('created_at', [$start, $end])
                ->count();
            $approved = \App\Models\RequestOrder::where('sales_id', $s->id)
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['approved_warehouse', 'completed']);
                })->count();
            
            $revenue = \App\Models\RequestOrder::where('sales_id', $s->id)
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('order', function($q) {
                    $q->whereIn('status', ['approved_warehouse', 'completed']);
                })->sum('subtotal');
            
            $percentage = $total > 0 ? round(($approved / $total) * 100, 2) : 0;
            
            $salesPerfData[] = [
                'name' => $s->name,
                'total' => $total,
                'approved' => $approved,
                'percentage' => $percentage,
                'revenue' => (float) $revenue
            ];
        }

        // 7. Recent Customer Activity (directly from Order table)
        $customerActivity = \App\Models\Order::with(['customer'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get()
            ->unique('customer_id')
            ->take(10);

        return view('dashboard.supervisor.index', [
            'totalPending' => $totalPending,
            'lastMonthPending' => $lastMonthPending,
            'totalApproved' => $totalApproved,
            'lastMonthApproved' => $lastMonthApproved,
            'totalRevenue' => $totalRevenue,
            'lastMonthRevenue' => $lastMonthRevenue,
            'salesPerformance' => $salesPerformance,
            'lastMonthPerf' => $lastMonthPerf,
            'imc_labels' => $months,
            'imc_masuk' => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data' => $svcData,
            'imc_years' => $imcYears,
            'selectedYear' => $selectedYear,
            'pendingOrders' => $pendingOrders,
            'salesPerfData' => $salesPerfData,
            'customerActivity' => $customerActivity,
            'selectedThreshold' => $threshold,
            'selectedDateStart' => $dateStartRaw,
            'selectedDateEnd' => $dateEndRaw,
        ]);
    }

    /**
     * AJAX endpoint for chart data.
     */
    public function chartData(Request $request)
    {
        $selectedYear = (int) $request->query('year', now()->year);
        $dateStartRaw = $request->query('date_start');
        $dateEndRaw = $request->query('date_end');
        
        $dateStart = null;
        $dateEnd = null;
        try {
            if ($dateStartRaw) $dateStart = \Carbon\Carbon::parse($dateStartRaw)->startOfDay();
            if ($dateEndRaw) $dateEnd = \Carbon\Carbon::parse($dateEndRaw)->endOfDay();
        } catch (\Exception $e) {}

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = \App\Models\RequestOrder::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');

            $imcKeluar[] = \App\Models\RequestOrder::whereHas('order', function($q) {
                    $q->whereIn('status', ['approved_warehouse', 'completed']);
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');
        }

        $svcQuery = \App\Models\Order::query();
        if ($dateStart && $dateEnd) {
            $svcQuery->whereBetween('created_at', [$dateStart, $dateEnd]);
        } else {
            $svcQuery->whereYear('created_at', $selectedYear);
        }

        $statusCounts = $svcQuery->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        $svcLabels = $statusCounts->pluck('status')->map(fn($s) => ucwords(str_replace('_', ' ', $s)))->toArray();
        $svcData = $statusCounts->pluck('total')->toArray();

        // Also return updated years for select
        $imcYears = \App\Models\RequestOrder::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($imcYears)) $imcYears = [now()->year];

        return response()->json([
            'imc_labels' => $months,
            'imc_masuk'  => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data'   => $svcData,
            'imc_years'  => $imcYears,
            'selectedYear' => $selectedYear,
        ]);
    }

    public function exportPerformance(Request $request)
    {
        $type = $request->query('type', 'monthly');
        return Excel::download(new SalesPerformanceExport($type), "Sales_Performance_{$type}_" . now()->format('Ymd') . ".xlsx");
    }

    public function exportQuotations()
    {
        return Excel::download(new QuotationsReportExport(), "All_Quotations_Report_" . now()->format('Ymd') . ".xlsx");
    }
}
