<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Goods;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\CustomQuotation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Exports\QuotationsReportExportSales;
use Maatwebsite\Excel\Facades\Excel;

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

        $applyDateFilter = function ($query, string $column = 'created_at') use ($dateStart, $dateEnd) {
            return $query
                ->when($dateStart, fn($q) => $q->where($column, '>=', $dateStart))
                ->when($dateEnd, fn($q) => $q->where($column, '<=', $dateEnd));
        };

        // 2. Status Constants
        $goalQuotationStatuses = ['completed', 'under_procurement','not_completed' ,'sent_to_warehouse', 'approved_warehouse', 'approved_supervisor'];
        $finishSalesOrderStatuses = ['completed'];
        $failedStatuses = ['rejected_supervisor', 'rejected_warehouse', 'canceled', 'partial_canceled'];
        $excludedProcessStatuses = ['completed', 'rejected_supervisor', 'rejected_warehouse', 'canceled', 'partial_canceled', 'not_completed', 'expired'];
        $activeCustomStatuses = ['pending_approval', 'sent_to_warehouse', 'open', 'approved_supervisor', 'approved_warehouse', 'under_procurement'];

        // Card 1: Quotation (Counts)
        $totalQuotation = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id))->count()
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->where('status', '!=', 'sent_to_quotation'))->count();

        $totalFailedQuotation = $applyDateFilter(\App\Models\Order::where('sales_id', $user->id)->whereIn('status', $failedStatuses))->count()
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $failedStatuses))->count();

        $totalGoalQuotation = $applyDateFilter(\App\Models\Order::where('sales_id', $user->id)->whereIn('status', $goalQuotationStatuses))->count()
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $goalQuotationStatuses))->count();

        // Card 2: Value Quotation (Nominal)
        $totalValueQuotation = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id))->sum('grand_total')
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->where('status', '!=', 'sent_to_quotation'))->sum('grand_total');

        $totalFailedValueQuotation = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id)->whereHas('order', fn($q) => $q->whereIn('status', $failedStatuses)))->sum('grand_total')
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $failedStatuses))->sum('grand_total');

        $totalGoalValueQuotation = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id)->whereHas('order', fn($q) => $q->whereIn('status', $goalQuotationStatuses)))->sum('grand_total')
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $goalQuotationStatuses))->sum('grand_total');

        // Card 3: Sales Order (Counts)
        $totalProcess = $applyDateFilter(\App\Models\Order::where('sales_id', $user->id)->whereNotIn('status', $excludedProcessStatuses))->count()
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereNotIn('status', $excludedProcessStatuses))->count();

        $totalFinish = $applyDateFilter(\App\Models\Order::where('sales_id', $user->id)->whereIn('status', $finishSalesOrderStatuses))->count()
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $finishSalesOrderStatuses))->count();

        $totalSalesOrder = $totalProcess + $totalFinish;

        // Card 4: Value Sales Order (Nominal)
        $totalProcessValueSalesOrder = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id)->whereHas('order', fn($q) => $q->whereNotIn('status', $excludedProcessStatuses)))->sum('grand_total')
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereNotIn('status', $excludedProcessStatuses))->sum('grand_total');

        $totalFinishValueSalesOrder = $applyDateFilter(\App\Models\Quotation::where('sales_id', $user->id)->whereHas('order', fn($q) => $q->whereIn('status', $finishSalesOrderStatuses)))->sum('grand_total')
            + $applyDateFilter(\App\Models\CustomQuotation::where('sales_id', $user->id)->whereIn('status', $finishSalesOrderStatuses))->sum('grand_total');

        $totalValueSalesOrder = $totalProcessValueSalesOrder + $totalFinishValueSalesOrder;

        // 6. Chart Data (IMC - Sales Performance)
        $selectedYear = (int) $request->query('year', now()->year);
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->whereHas('order', function ($q) use ($goalQuotationStatuses) {
                        $q->whereIn('status', $goalQuotationStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->whereIn('status', $goalQuotationStatuses)
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));

            $imcKeluar[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->whereHas('order', function ($q) use ($finishSalesOrderStatuses) {
                        $q->whereIn('status', $finishSalesOrderStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->whereIn('status', $finishSalesOrderStatuses)
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));
        }

        $imcYears = \App\Models\Quotation::where('sales_id', $user->id)
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->merge(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->selectRaw('YEAR(created_at) as year')
                    ->distinct()
                    ->pluck('year')
            )
            ->unique()
            ->sortDesc()
            ->map(fn($y) => (int)$y)
            ->values()
            ->toArray();
        if (empty($imcYears)) $imcYears = [now()->year];

        // 7. Chart Data (SVC - Best Sellers from Completed Orders)
        $topItems = \App\Models\QuotationItem::join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->where('quotations.sales_id', $user->id)
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

        // 8. Target Quarter & Monthly Targets (filtered by selectedStatus, default 'all')
        $selectedStatus = $request->query('status', 'all');
        $targetFilteredValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $targetFilteredValues[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->when($selectedStatus !== 'all', function ($q) use ($selectedStatus) {
                        $q->whereHas('order', fn($oq) => $oq->where('status', $selectedStatus));
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->when($selectedStatus !== 'all', fn($q) => $q->where('status', $selectedStatus))
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));
        }

        $quarterTargets = [
            'Q1' => $targetFilteredValues[0] + $targetFilteredValues[1] + $targetFilteredValues[2],
            'Q2' => $targetFilteredValues[3] + $targetFilteredValues[4] + $targetFilteredValues[5],
            'Q3' => $targetFilteredValues[6] + $targetFilteredValues[7] + $targetFilteredValues[8],
            'Q4' => $targetFilteredValues[9] + $targetFilteredValues[10] + $targetFilteredValues[11],
        ];

        $monthlyTargets = [
            'Q1' => [
                'Januari'  => $targetFilteredValues[0],
                'Februari' => $targetFilteredValues[1],
                'Maret'    => $targetFilteredValues[2],
            ],
            'Q2' => [
                'April' => $targetFilteredValues[3],
                'Mei'   => $targetFilteredValues[4],
                'Juni'  => $targetFilteredValues[5],
            ],
            'Q3' => [
                'Juli'      => $targetFilteredValues[6],
                'Agustus'   => $targetFilteredValues[7],
                'September' => $targetFilteredValues[8],
            ],
            'Q4' => [
                'Oktober'  => $targetFilteredValues[9],
                'November' => $targetFilteredValues[10],
                'Desember' => $targetFilteredValues[11],
            ],
        ];

        // 8. Table Data (Latest Quotation)
        $salesOrders = \App\Models\Quotation::where('sales_id', $user->id)
            ->with(['order', 'items'])
            ->when($dateStart, fn($q) => $q->where('created_at', '>=', $dateStart))
            ->when($dateEnd, fn($q) => $q->where('created_at', '<=', $dateEnd))
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.sales.index', [
            'totalQuotation'            => $totalQuotation,
            'totalFailedQuotation'      => $totalFailedQuotation,
            'totalGoalQuotation'        => $totalGoalQuotation,
            'totalValueQuotation'       => $totalValueQuotation,
            'totalFailedValueQuotation' => $totalFailedValueQuotation,
            'totalGoalValueQuotation'   => $totalGoalValueQuotation,
            'totalSalesOrder'           => $totalSalesOrder,
            'totalProcess'              => $totalProcess,
            'totalFinish'               => $totalFinish,
            'totalValueSalesOrder'        => $totalValueSalesOrder,
            'totalProcessValueSalesOrder' => $totalProcessValueSalesOrder,
            'totalFinishValueSalesOrder'  => $totalFinishValueSalesOrder,
            'imc_labels'                => $months,
            'imc_masuk'                 => $imcMasuk,
            'imc_keluar'                => $imcKeluar,
            'quarter_targets'           => $quarterTargets,
            'monthly_targets'           => $monthlyTargets,
            'svc_labels'                => $svcLabels,
            'svc_data'                  => $svcData,
            'imc_years'                 => $imcYears,
            'selectedYear'              => $selectedYear,
            'selectedStatus'            => $selectedStatus,
            'salesOrders'               => $salesOrders,
            'selectedDateStart'         => $dateStartRaw,
            'selectedDateEnd'           => $dateEndRaw,
        ]);
    }

    /**
     * AJAX endpoint for chart data.
     */
    public function chartData(Request $request)
    {
        $user = Auth::user();
        $selectedYear = (int) $request->query('year', now()->year);
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

        $applyDateFilter = function ($query, string $column = 'created_at') use ($dateStart, $dateEnd) {
            return $query
                ->when($dateStart, fn($q) => $q->where($column, '>=', $dateStart))
                ->when($dateEnd, fn($q) => $q->where($column, '<=', $dateEnd));
        };

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $goalQuotationStatuses = ['completed', 'under_procurement', 'not_completed', 'sent_to_warehouse', 'approved_warehouse', 'approved_supervisor'];
        $finishSalesOrderStatuses = ['completed'];
        $imcMasuk = [];
        $imcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $imcMasuk[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->whereHas('order', function ($q) use ($goalQuotationStatuses) {
                        $q->whereIn('status', $goalQuotationStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->whereIn('status', $goalQuotationStatuses)
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));

            $imcKeluar[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->whereHas('order', function ($q) use ($finishSalesOrderStatuses) {
                        $q->whereIn('status', $finishSalesOrderStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->whereIn('status', $finishSalesOrderStatuses)
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));
        }

        $topItems = \App\Models\QuotationItem::join('quotations', 'quotation_items.quotation_id', '=', 'quotations.id')
            ->join('orders', 'quotations.id', '=', 'orders.quotation_id')
            ->where('quotations.sales_id', $user->id)
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

        $selectedStatus = $request->query('status', 'all');
        $targetFilteredValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $targetFilteredValues[] = (float) ($applyDateFilter(
                \App\Models\Quotation::where('sales_id', $user->id)
                    ->when($selectedStatus !== 'all', function ($q) use ($selectedStatus) {
                        $q->whereHas('order', fn($oq) => $oq->where('status', $selectedStatus));
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total') + $applyDateFilter(
                \App\Models\CustomQuotation::where('sales_id', $user->id)
                    ->when($selectedStatus !== 'all', fn($q) => $q->where('status', $selectedStatus))
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
            )->sum('grand_total'));
        }

        $quarterTargets = [
            'Q1' => $targetFilteredValues[0] + $targetFilteredValues[1] + $targetFilteredValues[2],
            'Q2' => $targetFilteredValues[3] + $targetFilteredValues[4] + $targetFilteredValues[5],
            'Q3' => $targetFilteredValues[6] + $targetFilteredValues[7] + $targetFilteredValues[8],
            'Q4' => $targetFilteredValues[9] + $targetFilteredValues[10] + $targetFilteredValues[11],
        ];

        $monthlyTargets = [
            'Q1' => [
                'Januari'  => $targetFilteredValues[0],
                'Februari' => $targetFilteredValues[1],
                'Maret'    => $targetFilteredValues[2],
            ],
            'Q2' => [
                'April' => $targetFilteredValues[3],
                'Mei'   => $targetFilteredValues[4],
                'Juni'  => $targetFilteredValues[5],
            ],
            'Q3' => [
                'Juli'      => $targetFilteredValues[6],
                'Agustus'   => $targetFilteredValues[7],
                'September' => $targetFilteredValues[8],
            ],
            'Q4' => [
                'Oktober'  => $targetFilteredValues[9],
                'November' => $targetFilteredValues[10],
                'Desember' => $targetFilteredValues[11],
            ],
        ];

        return response()->json([
            'imc_labels' => $months,
            'imc_masuk'  => $imcMasuk,
            'imc_keluar' => $imcKeluar,
            'svc_labels' => $svcLabels,
            'svc_data'   => $svcData,
            'quarter_targets' => $quarterTargets,
            'monthly_targets' => $monthlyTargets,
            'selectedYear' => $selectedYear,
            'selectedStatus' => $selectedStatus,
        ]);
    }
}
