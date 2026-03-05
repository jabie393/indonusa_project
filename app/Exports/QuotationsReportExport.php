<?php

namespace App\Exports;

use App\Models\RequestOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuotationsReportExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RequestOrder::with(['sales', 'customer'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Nomor Penawaran',
            'Sales',
            'Customer',
            'Tanggal',
            'Subtotal',
            'Status',
        ];
    }

    public function map($ro): array
    {
        return [
            $ro->nomor_penawaran ?? '-',
            $ro->sales?->name ?? 'N/A',
            $ro->customer?->nama_customer ?? 'N/A',
            $ro->created_at->format('d/m/Y'),
            number_format($ro->subtotal, 2, ',', '.'),
            ucwords(str_replace('_', ' ', $ro->status)),
        ];
    }
}
