<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Order;
use App\Models\RequestOrder;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\RequestOrderItem;
use App\Models\User;
use Carbon\Carbon;

class GeneralAffairDashboardController extends Controller
{
    /**
     * Display the General Affair dashboard.
     */
    public function dashboard(Request $request = null)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Handle filters
        $threshold = (int) ($request ? $request->query('threshold', 20) : 20);
        $dateStartRaw = $request ? $request->query('date_start') : null;
        $dateEndRaw = $request ? $request->query('date_end') : null;

        $dateStart = null;
        $dateEnd = null;
        try {
            if ($dateStartRaw) $dateStart = Carbon::parse($dateStartRaw)->startOfDay();
            if ($dateEndRaw) $dateEnd = Carbon::parse($dateEndRaw)->endOfDay();
        } catch (\Exception $e) {
            $dateStart = null;
            $dateEnd = null;
        }

        // 2. Calculate Stats (Driven by RequestOrder)
        // Pending: No order yet, or still in approval stages
        $totalPending = RequestOrder::whereDoesntHave('order')
            ->orWhereHas('order', function($q) {
                $q->whereIn('status', ['pending_approval', 'sent_to_supervisor', 'sent_to_warehouse']);
            })->count();

        // Approved: Supervisor approved it, or moving through warehouse/completion
        $totalApproved = RequestOrder::whereHas('order', function($q) {
            $q->whereIn('status', ['approved_supervisor', 'sent_to_warehouse', 'approved_warehouse']);
        })->count();

        // Total Orders (All Quotations/Requests)
        $totalOrders = Order::whereIn('status', ['completed'])->count();

        // Total Revenue (Sum of all RequestOrder grand totals where order is completed)
        $totalRevenue = RequestOrder::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->sum('grand_total');

        // Customers (Only from completed orders)
        $totalCustomers = RequestOrder::whereHas('order', function($q) {
            $q->where('status', 'completed');
        })->distinct('customer_id')->count('customer_id');

        // 3. Top Performers (Strategic Insights)

        // Top 5 Sales Users by Revenue (Status: Completed)
        $topSales = User::where('role', 'Sales')
            ->join('request_orders', 'users.id', '=', 'request_orders.sales_id')
            ->join('orders', 'request_orders.id', '=', 'orders.request_order_id')
            ->where('orders.status', 'completed')
            ->select('users.name', DB::raw('SUM(request_orders.grand_total) as revenue'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // Top 5 Customers by Revenue (Completed)
        $topCustomers = RequestOrder::whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->select('customer_name', DB::raw('SUM(grand_total) as revenue'))
            ->groupBy('customer_name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $statusBreakdown = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        // 4. Chart Data (IMC - Volume Trend from Orders + History)
        $selectedYear = (int) ($request ? $request->query('year', now()->year) : now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $imcMasuk = []; 
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            // Potensi Pendapatan (Total Grand Total all RequestOrders)
            $imcMasuk[] = (float) RequestOrder::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('grand_total');

            // Pendapatan Selesai (Total Grand Total where order is completed)
            $imcKeluar[] = (float) RequestOrder::whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->sum('grand_total');
        }

        $imcYears = RequestOrder::whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn($y) => (int)$y)
            ->toArray();
        if (empty($imcYears)) $imcYears = [now()->year];

        // 5. Chart Data (SVC - Best Sellers from Completed Orders)
        $topItems = RequestOrderItem::join('request_orders', 'request_order_items.request_order_id', '=', 'request_orders.id')
            ->join('orders', 'request_orders.id', '=', 'orders.request_order_id')
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

        // 6. Recent History Logs
        $recentHistory = \App\Models\BarangHistory::with('user')
            ->latest('changed_at')
            ->take(10)
            ->get();


        // 8. Low Stock
        $lowStockItems = Barang::where('stok', '<=', $threshold)
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('dashboard.general-affair.index', [
            'totalPending' => $totalPending,
            'totalApproved' => $totalApproved,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalCustomers' => $totalCustomers,
            'topSales' => $topSales,
            'topCustomers' => $topCustomers,
            'statusBreakdown' => $statusBreakdown,
            'imc_labels' => $months,
            'imc_masuk' => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data' => $svcData,
            'imc_years' => $imcYears,
            'selectedYear' => $selectedYear,
            'recentHistory' => $recentHistory,
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
        $selectedYear = (int) $request->query('year', now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = RequestOrder::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
            $imcKeluar[] = Order::whereIn('status', ['approved_supervisor', 'approved_warehouse', 'completed'])
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
                ->count();
        }

        $topItems = SalesOrderItem::select('nama_barang', DB::raw('SUM(qty) as total_qty'))
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
