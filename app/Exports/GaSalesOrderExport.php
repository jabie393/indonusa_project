<?php

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class GaSalesOrderExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $search;
    protected $startDate;
    protected $endDate;
    protected $salesId;
    protected $status;
    protected $reportType;

    public function __construct(
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
        ?string $salesId = null,
        ?string $status = null,
        ?string $reportType = null
    ) {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->salesId = $salesId;
        $this->status = $status;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        $query = Quotation::with(['items', 'customer', 'order']);

        // Base filter for GA sales order invoices
        $query->where(function ($q) {
            $q->whereDoesntHave('order')
              ->orWhereHas('order', function ($o) {
                  $o->where('status', '!=', 'open')
                    ->where('status', '!=', 'sent_to_supervisor');
              });
        });

        // Search filter
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%{$search}%")
                  ->orWhere('quotation_number', 'like', "%{$search}%")
                  ->orWhere('sales_order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('no_po', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        // Sales agent filter
        if (!empty($this->salesId)) {
            $query->where('sales_id', $this->salesId);
        }

        // Report type filter
        if (!empty($this->reportType) && $this->reportType !== 'all') {
            if ($this->reportType === 'quotation') {
                $query->whereNull('custom_quotation_id');
            } elseif ($this->reportType === 'custom_quotation') {
                $query->whereNotNull('custom_quotation_id');
            }
        }

        // Status filter
        if (!empty($this->status) && $this->status !== 'all') {
            if ($this->status === 'belum_diproses') {
                $query->whereDoesntHave('order');
            } else {
                $statusVal = $this->status;
                $query->whereHas('order', function ($o) use ($statusVal) {
                    $o->where('status', $statusVal);
                });
            }
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Customer',
            'Request Order',
            'Nomor Quotation',
            'No Invoice',
            'No Kwitansi',
            'DO',
            'PO',
            'Sales Order',
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
        if ($row->valid_date) {
            $berlakuSampai = \Carbon\Carbon::parse($row->valid_date)->translatedFormat('d F Y');
        } elseif ($row->expired_at) {
            $berlakuSampai = \Carbon\Carbon::parse($row->expired_at)->translatedFormat('d F Y');
        }

        return [
            $rowNumber,
            $row->required_date ? $row->required_date->format('d/m/Y') : '-',
            $row->customer_name ?? '-',
            $row->request_number,
            $row->quotation_number,
            $row->order->no_invoice ?? '-',
            $row->order->no_receipt ?? '-',
            $row->order->do_number ?? '-',
            $row->no_po ?? '-',
            $row->sales_order_number ?? '-',
            $row->items->count(),
            $row->grand_total ?? 0,
            ($row->items && $row->items->count() > 0) ? ($row->items->first()->discount_percent ?? 0) : 0,
            $row->status, // Menggunakan accessor status ramah pengguna dari model Quotation
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
