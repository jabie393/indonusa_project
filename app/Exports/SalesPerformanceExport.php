<?php

namespace App\Exports;

use App\Models\User;
use App\Models\RequestOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesPerformanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type; // weekly or monthly

    public function __construct($type = 'monthly')
    {
        $this->type = $type;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::where('role', 'Sales')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Sales',
            'Range Waktu',
            'Total Quotations',
            'Completed Orders',
            'Not Completed Orders',
            'Success Rate (%)',
            'Total Revenue',
        ];
    }

    public function map($user): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();
        $rangeLabel = now()->format('F Y');

        if ($this->type === 'weekly') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
            $rangeLabel = 'Week ' . now()->weekOfYear . ' (' . now()->format('Y') . ')';
        }

        $totalQuotes = RequestOrder::where('sales_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $completed = RequestOrder::where('sales_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['approved_warehouse', 'completed']);
            })->count();

        $notCompleted = $totalQuotes - $completed;
        $rate = $totalQuotes > 0 ? round(($completed / $totalQuotes) * 100, 2) : 0;
        
        $revenue = RequestOrder::where('sales_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['approved_warehouse', 'completed']);
            })->sum('subtotal');

        return [
            $user->name,
            $rangeLabel,
            $totalQuotes,
            $completed,
            $notCompleted,
            $rate . '%',
            number_format($revenue, 2, ',', '.'),
        ];
    }
}
