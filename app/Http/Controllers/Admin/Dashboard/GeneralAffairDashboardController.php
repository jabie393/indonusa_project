<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\User;
use Carbon\Carbon;

class GeneralAffairDashboardController extends Controller
{
    /**
     * Display the General Affair dashboard.
     */
    public function dashboard(?Request $request = null)
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

        $applyDateFilter = function ($query, string $column = 'created_at') use ($dateStart, $dateEnd) {
            return $query
                ->when($dateStart, fn($q) => $q->where($column, '>=', $dateStart))
                ->when($dateEnd, fn($q) => $q->where($column, '<=', $dateEnd));
        };

        // 2. Calculate Stats (Driven by Quotation)
        // Pending: No order yet, or still in approval stages
        $totalPending = $applyDateFilter(
            Quotation::where(function ($query) {
                $query->whereDoesntHave('order')
                    ->orWhereHas('order', function($q) {
                        $q->whereIn('status', ['pending_approval', 'sent_to_supervisor', 'sent_to_warehouse']);
                    });
            })
        )->count();

        // Approved: Supervisor approved it, or moving through warehouse/completion
        $totalApproved = $applyDateFilter(Quotation::whereHas('order', function($q) {
            $q->whereIn('status', ['open', 'sent_to_warehouse', 'approved_warehouse']);
        }))->count();

        // Total Orders (All Quotations/Requests)
        $totalOrders = $applyDateFilter(Order::whereIn('status', ['completed']))->count();

        // Total Revenue (Sum of all Quotation grand totals where order is completed)
        $totalRevenue = $applyDateFilter(Quotation::whereHas('order', function($q) {
            $q->where('status', 'completed');
        }))->sum('grand_total');

        // Customers (Only from completed orders)
        $totalCustomers = $applyDateFilter(Quotation::whereHas('order', function($q) {
            $q->where('status', 'completed');
        }))->distinct('customer_id')->count('customer_id');

        // 3. Top Performers (Strategic Insights)

        // Top 5 Sales Users by Revenue (Status: Completed)
        $topSales = User::where('role', 'Sales')
            ->join('quotations', 'users.id', '=', 'quotations.sales_id')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->where('orders.status', 'completed')
            ->when($dateStart, fn($q) => $q->where('quotations.created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('quotations.created_at', '<=', $dateEnd))
            ->select('users.name', DB::raw('SUM(quotations.grand_total) as revenue'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // Top 5 Customers by Revenue (Completed)
        $topCustomers = $applyDateFilter(Quotation::whereHas('order', function($q) {
                $q->where('status', 'completed');
            }))
            ->select('customer_name', DB::raw('SUM(grand_total) as revenue'))
            ->groupBy('customer_name')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $statusBreakdown = $applyDateFilter(Order::select('status', DB::raw('count(*) as count')))
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
            // Potensi Pendapatan (Total Grand Total all Quotations)
            $imcMasuk[] = (float) $applyDateFilter(Quotation::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
            )->sum('grand_total');

            // Pendapatan Selesai (Total Grand Total where order is completed)
            $imcKeluar[] = (float) $applyDateFilter(Quotation::whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
            )->sum('grand_total');
        }

        $imcYears = Quotation::whereHas('order', function($q) {
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
        $topItems = QuotationItem::join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->where('orders.status', 'completed')
            ->when($dateStart, fn($q) => $q->where('quotations.created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('quotations.created_at', '<=', $dateEnd))
            ->leftJoin('goods', 'quotation_items.goods_id', '=', 'goods.id')
            ->select(
                DB::raw('COALESCE(goods.goods_name, quotation_items.custom_product_name) as item_name'), 
                DB::raw('SUM(quotation_items.quantity) as total_qty')
            )
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();
        $svcLabels = $topItems->pluck('item_name')->toArray();
        $svcData = $topItems->pluck('total_qty')->toArray();

        // 6. Recent History Logs
        $recentHistory = $applyDateFilter(\App\Models\GoodsHistory::with('user'), 'changed_at')
            ->latest('changed_at')
            ->take(10)
            ->get();


        // 8. Low Stock
        $lowStockItems = Goods::where('stock', '<=', $threshold)
            ->orderBy('stock')
            ->take(5)
            ->get();

        // 9. GA Specific Task Counts
        $procurementPendingCount = \App\Models\CustomQuotation::where('status', 'sent_to_quotation')
            ->whereHas('order', function ($query) {
                $query->where('status', 'under_procurement');
            })
            ->doesntHave('procurementOfGoods')
            ->count();

        $invoicePendingCount = \App\Models\DeliveryBatch::where(function ($q) {
            $q->whereNull('no_invoice')->orWhereNull('no_receipt');
        })->count();

        $procurementRejectedCount = \App\Models\ProcurementArrivalRequest::where('status', 'rejected')->count();

        $goodsInRevisionCount = \App\Models\Goods::where('goods_status', 'rejected')
            ->where('status_listing', '!=', 'non_listing')
            ->whereDoesntHave('procurementOfGoodsItems')
            ->count();

        \Illuminate\Support\Facades\Log::info("GA Dashboard Counts", [
            'procurementPending' => $procurementPendingCount,
            'invoicePending' => $invoicePendingCount,
            'procurementRejected' => $procurementRejectedCount,
            'goodsInRevision' => $goodsInRevisionCount
        ]);

        // 10. Purchasing / Procurement Metrics
        $validProcurementItems = \App\Models\ProcurementOfGoodsItem::whereHas('procurementOfGoods', function ($q) use ($dateStart, $dateEnd) {
            $q->where('status', '!=', 'canceled')
                ->when($dateStart, fn($sq) => $sq->where('created_at', '>=', $dateStart))
                ->when($dateEnd, fn($sq) => $sq->where('created_at', '<=', $dateEnd));
        });

        // Finish: value of goods actually received (including partial arrivals)
        $totalFinishValueProcurement = (float) ((clone $validProcurementItems)
            ->selectRaw('SUM(buy_price * qty_received) as total')
            ->value('total') ?? 0);

        // Pending: remaining value to be received (qty_ordered or fallback qty_requested minus qty_received)
        $totalPendingValueProcurement = (float) ((clone $validProcurementItems)
            ->selectRaw('SUM(buy_price * GREATEST(0, COALESCE(NULLIF(qty_ordered, 0), qty_requested) - qty_received)) as total')
            ->value('total') ?? 0);

        // Total: Pending + Finish
        $totalValueProcurement = $totalFinishValueProcurement + $totalPendingValueProcurement;

        // 11. Purchasing Trend Chart Data (Monthly spending: buy_price * qty_received)
        $purchasingYears = \App\Models\ProcurementOfGoods::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($purchasingYears)) {
            $purchasingYears = [(int) now()->year];
        }

        $purchasingMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyPurchasingSpending = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyPurchasingSpending[] = (float) (\App\Models\ProcurementOfGoodsItem::whereHas('procurementOfGoods', function ($q) use ($selectedYear, $m) {
                $q->where('status', '!=', 'canceled')
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m);
            })->selectRaw('SUM(buy_price * qty_received) as total')->value('total') ?? 0);
        }

        return view('dashboard.general-affair.index', [
            'procurementPendingCount' => $procurementPendingCount,
            'invoicePendingCount' => $invoicePendingCount,
            'procurementRejectedCount' => $procurementRejectedCount,
            'goodsInRevisionCount' => $goodsInRevisionCount,
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
            'totalValueProcurement' => $totalValueProcurement,
            'totalPendingValueProcurement' => $totalPendingValueProcurement,
            'totalFinishValueProcurement' => $totalFinishValueProcurement,
            'purchasing_years' => $purchasingYears,
            'purchasing_months' => $purchasingMonths,
            'monthly_purchasing_spending' => $monthlyPurchasingSpending,
            'timeline_values' => $this->calculateAverageTimeline($dateStart, $dateEnd),
            'purchasing_categories' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd)['categories'],
            'purchasing_category_labels' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd)['labels'],
            'purchasing_category_values' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd)['values'],
            'purchasing_category_colors' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd)['colors'],
            'purchasing_category_has_data' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd)['has_data'],
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
            if ($dateStartRaw) $dateStart = Carbon::parse($dateStartRaw)->startOfDay();
            if ($dateEndRaw) $dateEnd = Carbon::parse($dateEndRaw)->endOfDay();
        } catch (\Exception $e) {
            $dateStart = null;
            $dateEnd = null;
        }

        $applyDateFilter = function ($query, string $column = 'created_at') use ($dateStart, $dateEnd) {
            return $query
                ->when($dateStart, fn($q) => $q->where($column, '>=', $dateStart))
                ->when($dateEnd, fn($q) => $q->where($column, '<=', $dateEnd));
        };

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = (float) $applyDateFilter(Quotation::whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
            )->sum('grand_total');
            $imcKeluar[] = (float) $applyDateFilter(Quotation::whereHas('order', function($q) {
                    $q->where('status', 'completed');
                })
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $m)
            )->sum('grand_total');
        }

        $topItems = QuotationItem::join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->where('orders.status', 'completed')
            ->when($dateStart, fn($q) => $q->where('quotations.created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('quotations.created_at', '<=', $dateEnd))
            ->leftJoin('goods', 'quotation_items.goods_id', '=', 'goods.id')
            ->select(
                DB::raw('COALESCE(goods.goods_name, quotation_items.custom_product_name) as item_name'), 
                DB::raw('SUM(quotation_items.quantity) as total_qty')
            )
            ->groupBy('item_name')
            ->orderByDesc('total_qty')
            ->take(8)
            ->get();
        $svcLabels = $topItems->pluck('item_name')->toArray();
        $svcData = $topItems->pluck('total_qty')->toArray();

        $purchasingYears = \App\Models\ProcurementOfGoods::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();
        if (empty($purchasingYears)) {
            $purchasingYears = [(int) now()->year];
        }

        $purchasingMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyPurchasingSpending = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyPurchasingSpending[] = (float) (\App\Models\ProcurementOfGoodsItem::whereHas('procurementOfGoods', function ($q) use ($selectedYear, $m) {
                $q->where('status', '!=', 'canceled')
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m);
            })->selectRaw('SUM(buy_price * qty_received) as total')->value('total') ?? 0);
        }

        return response()->json([
            'imc_labels' => $months,
            'imc_masuk'  => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data'   => $svcData,
            'selectedYear' => $selectedYear,
            'purchasing_years' => $purchasingYears,
            'purchasing_months' => $purchasingMonths,
            'purchasing_spending' => $monthlyPurchasingSpending,
            'timeline_values' => $this->calculateAverageTimeline($dateStart, $dateEnd),
            'purchasing_categories' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd),
        ]);
    }

    /**
     * Calculate Top Product Categories from Approved Arrival Requests
     */
    private function calculateTopPurchasingCategories($dateStart = null, $dateEnd = null): array
    {
        $rawCategories = \App\Models\ProcurementArrivalRequest::where('procurement_arrival_requests.status', 'approved')
            ->join('goods', 'procurement_arrival_requests.good_id', '=', 'goods.id')
            ->when($dateStart, fn($q) => $q->where('procurement_arrival_requests.created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('procurement_arrival_requests.created_at', '<=', $dateEnd))
            ->select(
                DB::raw("COALESCE(NULLIF(TRIM(goods.category), ''), 'Lainnya') as category_name"),
                DB::raw("SUM(procurement_arrival_requests.quantity * procurement_arrival_requests.unit_cost) as total_spending")
            )
            ->groupBy('category_name')
            ->orderByDesc('total_spending')
            ->get();

        $totalSpending = (float) $rawCategories->sum('total_spending');

        $palette = [
            '#3b82f6', // Blue
            '#10b981', // Green
            '#f97316', // Orange
            '#a855f7', // Purple
            '#6b7280'  // Grey for Lainnya
        ];

        if ($totalSpending <= 0 || $rawCategories->isEmpty()) {
            return [
                'categories' => [],
                'labels' => ['Belum Ada Data'],
                'values' => [1],
                'colors' => ['#e5e7eb'],
                'total_spending' => 0,
                'has_data' => false,
            ];
        }

        $topCategories = [];
        $top4 = $rawCategories->take(4);
        $others = $rawCategories->slice(4);

        $colorIndex = 0;
        foreach ($top4 as $item) {
            $spending = (float) $item->total_spending;
            $pct = round(($spending / $totalSpending) * 100, 1);
            $topCategories[] = [
                'name' => $item->category_name,
                'value' => $spending,
                'percentage' => $pct,
                'color' => $palette[$colorIndex] ?? '#6b7280',
            ];
            $colorIndex++;
        }

        if ($others->isNotEmpty()) {
            $otherSpending = (float) $others->sum('total_spending');
            $otherPct = round(($otherSpending / $totalSpending) * 100, 1);
            $topCategories[] = [
                'name' => 'Lainnya',
                'value' => $otherSpending,
                'percentage' => $otherPct,
                'color' => '#6b7280',
            ];
        }

        return [
            'categories' => $topCategories,
            'labels' => array_column($topCategories, 'name'),
            'values' => array_column($topCategories, 'value'),
            'colors' => array_column($topCategories, 'color'),
            'total_spending' => $totalSpending,
            'has_data' => true,
        ];
    }

    /**
     * Calculate Average Timeline SO - GR:
     * Point 1: Sales Order ke Purchasing (Day 0)
     * Point 2: Purchase Order ke Vendor (Average days from SO to PO)
     * Point 3: Barang Tiba dari Vendor (Average cumulative days from SO to first arrival request)
     */
    private function calculateAverageTimeline($dateStart = null, $dateEnd = null): array
    {
        $timelineProcurements = \App\Models\ProcurementOfGoods::where('status', '!=', 'canceled')
            ->when($dateStart, fn($q) => $q->where('created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('created_at', '<=', $dateEnd))
            ->with(['order', 'customQuotation.order', 'items.procurementArrivalRequests'])
            ->get();

        $soToPoDiffs = [];
        $soToArrivalDiffs = [];

        foreach ($timelineProcurements as $p) {
            $soDate = $p->order?->created_at ?? $p->customQuotation?->order?->created_at;
            if (!$soDate) {
                $soDateStr = DB::table('procurement_order_items')
                    ->join('procurement_of_goods_items', 'procurement_order_items.procurement_of_goods_item_id', '=', 'procurement_of_goods_items.id')
                    ->join('order_items', 'procurement_order_items.order_item_id', '=', 'order_items.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('procurement_of_goods_items.procurement_of_goods_id', $p->id)
                    ->value('orders.created_at');
                if ($soDateStr) {
                    $soDate = Carbon::parse($soDateStr);
                }
            }

            if ($soDate && $p->created_at) {
                $soToPoDiffs[] = max(0, $soDate->diffInSeconds($p->created_at) / 86400);
                $minArrival = $p->items->flatMap->procurementArrivalRequests
                    ->where('status', 'approved')
                    ->whereNotNull('received_at')
                    ->min('received_at');
                if ($minArrival) {
                    $arrivalDate = Carbon::parse($minArrival);
                    $soToArrivalDiffs[] = max(0, $soDate->diffInSeconds($arrivalDate) / 86400);
                }
            }
        }

        $avgSoToPo = !empty($soToPoDiffs) ? round(array_sum($soToPoDiffs) / count($soToPoDiffs), 1) : 0;
        $avgSoToArrival = !empty($soToArrivalDiffs) ? round(array_sum($soToArrivalDiffs) / count($soToArrivalDiffs), 1) : 0;

        return [0, $avgSoToPo, $avgSoToArrival];
    }
}
