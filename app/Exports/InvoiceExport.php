<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class InvoiceExport implements FromView, WithDrawings, WithColumnWidths, WithStyles, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.exports.invoice-excel', $this->data);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('images/Logo_transparent.png'));
        $drawing->setHeight(95);
        $drawing->setCoordinates('A1'); // Mapping ss:Index="2" Row 1
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Standard styles will be applied via the view
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                
                // Set Print Area: A1 to I{lastRow}
                $sheet->getPageSetup()->setPrintArea("A1:I{$highestRow}");
                
                // Centering & Scaling
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(75); // 75% Scale as per new Invoice.xml
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                
                // Margins (Top: 1.4", Left/Right: 0.43", Bottom: 0.75")
                $sheet->getPageMargins()->setTop(0.43);
                $sheet->getPageMargins()->setBottom(0.43);
                $sheet->getPageMargins()->setLeft(0.43);
                $sheet->getPageMargins()->setRight(0.43);
            },
        ];
    }
}
