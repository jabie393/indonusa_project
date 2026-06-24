<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromView, ShouldAutoSize, WithEvents
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
                // Ensure alignment and formatting are applied properly if needed
                $sheet = $event->sheet->getDelegate();
                
                // You can perform advanced formatting on the sheet here if needed
                // E.g. gridlines, print setup
                $sheet->setShowGridlines(true);
            },
        ];
    }
}
