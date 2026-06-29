<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromView, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Render view as Excel spreadsheet
     */
    public function view(): View
    {
        return view('admin.exports.sales-report-excel', $this->data);
    }

    /**
     * Register after-sheet events for styling
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set explicit column widths to prevent squished cells from colspan headers
                $widths = [
                    'A' => 6,
                    'B' => 15,
                    'C' => 22,
                    'D' => 22,
                    'E' => 22,
                    'F' => 22,
                    'G' => 18,
                    'H' => 28,
                    'I' => 32,
                    'J' => 18,
                    'K' => 8,
                    'L' => 12,
                    'M' => 20,
                    'N' => 20,
                    'O' => 15,
                ];

                foreach ($widths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                $sheet->setShowGridlines(true);
            },
        ];
    }
}
