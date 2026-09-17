<?php
namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Quotation;
use App\Models\CustomQuotation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
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

        // Ensure start is before end
        if ($dateStart && $dateEnd && $dateStart->gt($dateEnd)) {
            $temp = clone $dateStart;
            $dateStart = clone $dateEnd->startOfDay();
            $dateEnd = clone $temp->endOfDay();
        }

        // 2. Sales List and Section Data
        $salesList = \App\Models\User::where('role', 'Sales')->orderBy('name')->get();
        $salesData = $this->getSalesSectionData($request, $dateStart, $dateEnd);

        return view('dashboard.supervisor.index', array_merge([
            'selectedDateStart' => $dateStartRaw,
            'selectedDateEnd' => $dateEndRaw,
            'salesList' => $salesList,
        ], $salesData));
    }

    /**
     * AJAX endpoint for chart data.
     */
    public function chartData(Request $request)
    {
        $dateStartRaw = $request->query('date_start');
        $dateEndRaw = $request->query('date_end');
        
        $dateStart = null;
        $dateEnd = null;
        try {
            if ($dateStartRaw) $dateStart = \Carbon\Carbon::parse($dateStartRaw)->startOfDay();
            if ($dateEndRaw) $dateEnd = \Carbon\Carbon::parse($dateEndRaw)->endOfDay();
        } catch (\Exception $e) {}

        // Ensure start is before end
        if ($dateStart && $dateEnd && $dateStart->gt($dateEnd)) {
            $temp = clone $dateStart;
            $dateStart = clone $dateEnd->startOfDay();
            $dateEnd = clone $temp->endOfDay();
        }

        $salesSectionData = $this->getSalesSectionData($request, $dateStart, $dateEnd);

        return response()->json($salesSectionData);
    }

    /**
     * Helper method to calculate Sales Section metrics for all or specific sales.
     */
    private function getSalesSectionData(Request $request, $dateStart = null, $dateEnd = null)
    {
        $salesId = $request->query('sales_id', 'all');
        $selectedYear = (int) $request->query('year', now()->year);
        $selectedStatus = $request->query('status', 'all');

        $applyDateFilter = function ($query, string $column = 'created_at') use ($dateStart, $dateEnd) {
            return $query
                ->when($dateStart, fn($q) => $q->where($column, '>=', $dateStart))
                ->when($dateEnd, fn($q) => $q->where($column, '<=', $dateEnd));
        };

        $applySalesFilter = function ($query, string $column = 'sales_id') use ($salesId) {
            return $query->when($salesId !== 'all' && !empty($salesId), fn($q) => $q->where($column, $salesId));
        };

        // Status Constants
        $goalQuotationStatuses = ['completed', 'under_procurement', 'not_completed', 'sent_to_warehouse', 'approved_warehouse', 'approved_supervisor'];
        $finishSalesOrderStatuses = ['completed'];
        $failedStatuses = ['rejected_supervisor', 'rejected_warehouse', 'canceled', 'partial_canceled'];
        $excludedProcessStatuses = ['completed', 'rejected_supervisor', 'rejected_warehouse', 'canceled', 'partial_canceled', 'not_completed', 'expired'];

        // Card 1: Quotation (Counts)
        $totalQuotation = $applyDateFilter($applySalesFilter(\App\Models\Quotation::query()))->count()
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::where('status', '!=', 'sent_to_quotation')))->count();

        $totalFailedQuotation = $applyDateFilter($applySalesFilter(\App\Models\Order::whereIn('status', $failedStatuses)))->count()
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $failedStatuses)))->count();

        $totalGoalQuotation = $applyDateFilter($applySalesFilter(\App\Models\Order::whereIn('status', $goalQuotationStatuses)))->count()
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $goalQuotationStatuses)))->count();

        // Card 2: Value Quotation (Nominal)
        $totalValueQuotation = $applyDateFilter($applySalesFilter(\App\Models\Quotation::query()))->sum('grand_total')
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::where('status', '!=', 'sent_to_quotation')))->sum('grand_total');

        $totalFailedValueQuotation = $applyDateFilter($applySalesFilter(\App\Models\Quotation::whereHas('order', fn($q) => $q->whereIn('status', $failedStatuses))))->sum('grand_total')
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $failedStatuses)))->sum('grand_total');

        $totalGoalValueQuotation = $applyDateFilter($applySalesFilter(\App\Models\Quotation::whereHas('order', fn($q) => $q->whereIn('status', $goalQuotationStatuses))))->sum('grand_total')
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $goalQuotationStatuses)))->sum('grand_total');

        // Card 3: Sales Order (Counts)
        $totalProcess = $applyDateFilter($applySalesFilter(\App\Models\Order::whereNotIn('status', $excludedProcessStatuses)))->count()
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereNotIn('status', $excludedProcessStatuses)))->count();

        $totalFinish = $applyDateFilter($applySalesFilter(\App\Models\Order::whereIn('status', $finishSalesOrderStatuses)))->count()
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $finishSalesOrderStatuses)))->count();

        $totalSalesOrder = $totalProcess + $totalFinish;

        // Card 4: Value Sales Order (Nominal)
        $totalProcessValueSalesOrder = $applyDateFilter($applySalesFilter(\App\Models\Quotation::whereHas('order', fn($q) => $q->whereNotIn('status', $excludedProcessStatuses))))->sum('grand_total')
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereNotIn('status', $excludedProcessStatuses)))->sum('grand_total');

        $totalFinishValueSalesOrder = $applyDateFilter($applySalesFilter(\App\Models\Quotation::whereHas('order', fn($q) => $q->whereIn('status', $finishSalesOrderStatuses))))->sum('grand_total')
            + $applyDateFilter($applySalesFilter(\App\Models\CustomQuotation::whereIn('status', $finishSalesOrderStatuses)))->sum('grand_total');

        $totalValueSalesOrder = $totalProcessValueSalesOrder + $totalFinishValueSalesOrder;

        // Sales Performance Chart (#salesIMC)
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $salesImcMasuk = [];
        $salesImcKeluar = [];
        for ($m = 1; $m <= 12; $m++) {
            $salesImcMasuk[] = (float) ($applyDateFilter(
                $applySalesFilter(
                    \App\Models\Quotation::whereHas('order', function ($q) use ($goalQuotationStatuses) {
                        $q->whereIn('status', $goalQuotationStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
                )
            )->sum('grand_total') + $applyDateFilter(
                $applySalesFilter(
                    \App\Models\CustomQuotation::whereIn('status', $goalQuotationStatuses)
                        ->whereYear('created_at', $selectedYear)
                        ->whereMonth('created_at', $m)
                )
            )->sum('grand_total'));

            $salesImcKeluar[] = (float) ($applyDateFilter(
                $applySalesFilter(
                    \App\Models\Quotation::whereHas('order', function ($q) use ($finishSalesOrderStatuses) {
                        $q->whereIn('status', $finishSalesOrderStatuses);
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
                )
            )->sum('grand_total') + $applyDateFilter(
                $applySalesFilter(
                    \App\Models\CustomQuotation::whereIn('status', $finishSalesOrderStatuses)
                        ->whereYear('created_at', $selectedYear)
                        ->whereMonth('created_at', $m)
                )
            )->sum('grand_total'));
        }

        $salesImcYears = $applySalesFilter(\App\Models\Quotation::query())
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->merge(
                $applySalesFilter(\App\Models\CustomQuotation::query())
                    ->selectRaw('YEAR(created_at) as year')
                    ->distinct()
                    ->pluck('year')
            )
            ->unique()
            ->sortDesc()
            ->map(fn($y) => (int)$y)
            ->values()
            ->toArray();
        if (empty($salesImcYears)) $salesImcYears = [now()->year];

        // Target Quarter & Monthly Targets Chart
        $targetFilteredValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $targetFilteredValues[] = (float) ($applyDateFilter(
                $applySalesFilter(
                    \App\Models\Quotation::when($selectedStatus !== 'all', function ($q) use ($selectedStatus) {
                        $q->whereHas('order', fn($oq) => $oq->where('status', $selectedStatus));
                    })
                    ->whereYear('created_at', $selectedYear)
                    ->whereMonth('created_at', $m)
                )
            )->sum('grand_total') + $applyDateFilter(
                $applySalesFilter(
                    \App\Models\CustomQuotation::when($selectedStatus !== 'all', fn($q) => $q->where('status', $selectedStatus))
                        ->whereYear('created_at', $selectedYear)
                        ->whereMonth('created_at', $m)
                )
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

        return [
            'selectedSalesId'             => $salesId,
            'totalQuotation'              => $totalQuotation,
            'totalFailedQuotation'        => $totalFailedQuotation,
            'totalGoalQuotation'          => $totalGoalQuotation,
            'totalValueQuotation'         => $totalValueQuotation,
            'totalFailedValueQuotation'   => $totalFailedValueQuotation,
            'totalGoalValueQuotation'     => $totalGoalValueQuotation,
            'totalSalesOrder'             => $totalSalesOrder,
            'totalProcess'                => $totalProcess,
            'totalFinish'                 => $totalFinish,
            'totalValueSalesOrder'        => $totalValueSalesOrder,
            'totalProcessValueSalesOrder' => $totalProcessValueSalesOrder,
            'totalFinishValueSalesOrder'  => $totalFinishValueSalesOrder,
            'sales_imc_labels'            => $months,
            'sales_imc_masuk'             => $salesImcMasuk,
            'sales_imc_keluar'            => $salesImcKeluar,
            'sales_imc_years'             => $salesImcYears,
            'sales_quarter_targets'       => $quarterTargets,
            'sales_monthly_targets'       => $monthlyTargets,
            'selectedSalesYear'           => $selectedYear,
            'selectedSalesStatus'         => $selectedStatus,
        ];
    }
}
