<?php

namespace App\Exports;

use App\Models\RequestOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class GaSalesOrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $search;

    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = RequestOrder::with(['items', 'customer']);

        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('nomor_penawaran', 'like', "%{$search}%")
                  ->orWhere('sales_order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('no_po', 'like', "%{$search}%");
            });
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Request Order',
            'Nomor Penawaran',
            'PO',
            'Sales Order',
            'Tanggal',
            'Customer',
            'Jumlah Item',
            'Total',
            'Diskon %',
            'Status',
            'Berlaku Sampai',
        ];
    }

    public function map($row): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $berlakuSampai = '-';
        if ($row->tanggal_berlaku) {
            $berlakuSampai = \Carbon\Carbon::parse($row->tanggal_berlaku)->translatedFormat('d F Y');
        } elseif ($row->expired_at) {
            $berlakuSampai = \Carbon\Carbon::parse($row->expired_at)->translatedFormat('d F Y');
        }

        return [
            $rowNumber,
            $row->request_number,
            $row->nomor_penawaran,
            $row->no_po ?? '-',
            $row->sales_order_number ?? '-',
            $row->tanggal_kebutuhan ? $row->tanggal_kebutuhan->format('d/m/Y') : '-',
            $row->customer_name ?? '-',
            $row->items->count(),
            $row->grand_total ?? 0,
            $row->items->first()->diskon_percent ?? 0,
            $row->status,
            $berlakuSampai,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '225A97'],
                ],
            ],
        ];
    }
}
