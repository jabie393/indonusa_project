<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;

class SalesDashboardController extends Controller
{
    /**
     * Display the sales dashboard.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Handle filters
        $threshold = (int) $request->query('threshold', 20);
        $dateStartRaw = $request->query('date_start');
        $dateEndRaw = $request->query('date_end');

        $dateStart = null;
        $dateEnd = null;
        try {
            if ($dateStartRaw) $dateStart = \Carbon\Carbon::parse($dateStartRaw)->startOfDay();
            if ($dateEndRaw) $dateEnd = \Carbon\Carbon::parse($dateEndRaw)->endOfDay();
        } catch (\Exception $e) {
            $dateStart = null;
            $dateEnd = null;
        }

        // 2. Calculate Stats
        // Ranges for comparison
        $currentMonthStart = \Carbon\Carbon::now()->startOfMonth();
        $currentMonthEnd = \Carbon\Carbon::now()->endOfMonth();
        $lastMonthStart = \Carbon\Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = \Carbon\Carbon::now()->subMonth()->endOfMonth();

        // Total orders (Order model + CustomPenawaran)
        $totalQuotationQuery = \App\Models\Order::where('sales_id', $user->id);
        $totalCustomQuotationQuery = \App\Models\CustomPenawaran::where('sales_id', $user->id)
            ->whereIn('status', ['pending_approval', 'sent_to_warehouse', 'open','approved_supervisor', 'approved_warehouse']);

        $totalQuotation = $totalQuotationQuery->count() + $totalCustomQuotationQuery->count();
        $lastMonthQuotation = (clone $totalQuotationQuery)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count()
            + (clone $totalCustomQuotationQuery)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        // Approved Orders (Order model + CustomPenawaran)
        $approvedOrderQuery = \App\Models\Order::where('sales_id', $user->id)
            ->whereIn('status', ['approved_supervisor', 'approved_warehouse', 'open']);
        $approvedCustomQuery = \App\Models\CustomPenawaran::where('sales_id', $user->id)
            ->whereIn('status', ['approved_supervisor', 'open']);

        $totalApproved = $approvedOrderQuery->count() + $approvedCustomQuery->count();
        $lastMonthApproved = (clone $approvedOrderQuery)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count()
            + (clone $approvedCustomQuery)->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();

        // Total Sales (Completed Orders count)
        $salesQuery = \App\Models\Order::where('sales_id', $user->id)
            ->where('status', 'completed');
        $totalSales = $salesQuery->count();
        $lastMonthSales = (clone $salesQuery)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        // Total Profit (Sum of subtotal from completed RequestOrders)
        $profitQuery = \App\Models\RequestOrder::where('sales_id', $user->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            });
        $totalProfit = $profitQuery->sum('subtotal');
        $lastMonthProfit = (clone $profitQuery)
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('subtotal');

        // 3. Chart Data (IMC - Sales Performance)
        $selectedYear = (int) $request->query('year', now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $imcMasuk = []; // We'll use this for 'Request'
        $imcKeluar = []; // We'll use this for 'Approved'
        for ($m = 1; $m <= 12; $m++) {
            // Potensi Laba (Subtotal all RequestOrders)
            $imcMasuk[] = (float) \App\Models\RequestOrder::where('sales_id', $user->id)
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');

            // Laba Selesai (Subtotal where associated order is completed)
            $imcKeluar[] = (float) \App\Models\RequestOrder::where('sales_id', $user->id)
                ->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('subtotal');
        }

        $imcYears = \App\Models\RequestOrder::where('sales_id', $user->id)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn($y) => (int)$y)
            ->toArray();
        if (empty($imcYears)) $imcYears = [now()->year];

        // 4. Chart Data (SVC - Best Sellers from Completed Orders)
        $topItems = \App\Models\RequestOrderItem::join('request_orders', 'request_order_items.request_order_id', '=', 'request_orders.id')
            ->join('orders', 'request_orders.id', '=', 'orders.request_order_id')
            ->where('request_orders.sales_id', $user->id)
            ->where('orders.status', 'completed')
            ->leftJoin('goods', 'request_order_items.barang_id', '=', 'goods.id')
            ->select(
                DB::raw('COALESCE(goods.nama_barang, request_order_items.nama_barang_custom) as item_name'), 
                DB::raw('SUM(request_order_items.quantity) as total_qty')
            )
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();
        $svcLabels = $topItems->pluck('item_name')->toArray();
        $svcData = $topItems->pluck('total_qty')->toArray();

        // 5. Table Data (Latest Request Orders)
        $salesOrders = \App\Models\RequestOrder::where('sales_id', $user->id)
            ->with(['order', 'items'])
            ->latest()
            ->take(10)
            ->get();

        // 6. Low Stock (for the filter if needed, although not explicitly shown in cards)
        $lowStockItems = \App\Models\Barang::where('stok', '<=', $threshold)
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('dashboard.sales.index', [
            'totalQuotation' => $totalQuotation,
            'lastMonthQuotation' => $lastMonthQuotation,
            'totalApproved' => $totalApproved,
            'lastMonthApproved' => $lastMonthApproved,
            'totalSales' => $totalSales,
            'lastMonthSales' => $lastMonthSales,
            'totalProfit' => $totalProfit,
            'lastMonthProfit' => $lastMonthProfit,
            'imc_labels' => $months,
            'imc_masuk' => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data' => $svcData,
            'imc_years' => $imcYears,
            'selectedYear' => $selectedYear,
            'salesOrders' => $salesOrders,
            'lowStockItems' => $lowStockItems,
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
        $user = Auth::user();
        $selectedYear = (int) $request->query('year', now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = \App\Models\RequestOrder::where('sales_id', $user->id)
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
            $imcKeluar[] = \App\Models\Order::where('sales_id', $user->id)
                ->whereIn('status', ['approved_supervisor', 'approved_warehouse', 'completed'])
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
        }

        $topItems = \App\Models\SalesOrderItem::join('sales_orders', 'sales_order_items.sales_order_id', '=', 'sales_orders.id')
            ->where('sales_orders.sales_id', $user->id)
            ->select('nama_barang', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('nama_barang')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();
        $svcLabels = $topItems->pluck('nama_barang')->toArray();
        $svcData = $topItems->pluck('total_qty')->toArray();

        return response()->json([
            'imc_labels' => $months,
            'imc_masuk'  => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data'   => $svcData,
            'selectedYear' => $selectedYear,
        ]);
    }
}
