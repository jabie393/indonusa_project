<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Goods;
use App\Models\ProcurementOfGoods;
use App\Models\ProcurementOfGoodsItem;
use App\Models\ProcurementArrivalRequest;
use App\Models\Vendor;
use Carbon\Carbon;

class GeneralAffairDashboardController extends Controller
{
    /**
     * Display the General Affair dashboard (Purchasing only).
     */
    public function dashboard(?Request $request = null)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Handle filters
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

        $selectedYear = (int) ($request ? $request->query('year', now()->year) : now()->year);

        // 2. Purchasing / Procurement Metrics
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

        // 3. Purchasing Trend Chart Data (Monthly spending: buy_price * qty_received)
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

        $topCategoriesData = $this->calculateTopPurchasingCategories($dateStart, $dateEnd);

        return view('dashboard.general-affair.index', [
            'selectedYear' => $selectedYear,
            'selectedDateStart' => $dateStartRaw,
            'selectedDateEnd' => $dateEndRaw,
            'totalValueProcurement' => $totalValueProcurement,
            'totalPendingValueProcurement' => $totalPendingValueProcurement,
            'totalFinishValueProcurement' => $totalFinishValueProcurement,
            'purchasing_years' => $purchasingYears,
            'purchasing_months' => $purchasingMonths,
            'monthly_purchasing_spending' => $monthlyPurchasingSpending,
            'timeline_values' => $this->calculateAverageTimeline($dateStart, $dateEnd),
            'purchasing_categories' => $topCategoriesData['categories'],
            'purchasing_category_labels' => $topCategoriesData['labels'],
            'purchasing_category_values' => $topCategoriesData['values'],
            'purchasing_category_colors' => $topCategoriesData['colors'],
            'purchasing_category_has_data' => $topCategoriesData['has_data'],
            'vendor_stats' => $this->calculateVendorStats(),
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
            'selectedYear' => $selectedYear,
            'purchasing_years' => $purchasingYears,
            'purchasing_months' => $purchasingMonths,
            'purchasing_spending' => $monthlyPurchasingSpending,
            'timeline_values' => $this->calculateAverageTimeline($dateStart, $dateEnd),
            'purchasing_categories' => $this->calculateTopPurchasingCategories($dateStart, $dateEnd),
            'vendor_stats' => $this->calculateVendorStats(),
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

    /**
     * Calculate Vendor Database metrics (PKP vs Non PKP).
     */
    private function calculateVendorStats(): array
    {
        $totalVendors = (int) Vendor::count();
        $pkpCount = (int) Vendor::where('tax_status', 'PKP')->count();
        $nonPkpCount = max(0, $totalVendors - $pkpCount);

        $pkpPercent = $totalVendors > 0 ? round(($pkpCount / $totalVendors) * 100, 1) : 0;
        $nonPkpPercent = $totalVendors > 0 ? round(($nonPkpCount / $totalVendors) * 100, 1) : 0;

        $hasData = $totalVendors > 0;

        return [
            'total' => $totalVendors,
            'pkp_count' => $pkpCount,
            'non_pkp_count' => $nonPkpCount,
            'pkp_percentage' => $pkpPercent,
            'non_pkp_percentage' => $nonPkpPercent,
            'labels' => $hasData
                ? ["PKP (" . number_format($pkpCount) . " Vendors)", "Non PKP (" . number_format($nonPkpCount) . " Vendors)"]
                : ['Belum Ada Data'],
            'values' => $hasData
                ? [$pkpCount, $nonPkpCount]
                : [1],
            'colors' => $hasData
                ? ['#225A97', '#f97316']
                : ['#e5e7eb'],
            'has_data' => $hasData,
        ];
    }
}
