<?php

namespace App\Exports;

use App\Models\RequestOrder;
use App\Models\OrderItem;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuotationsReportExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 1. Get Orders first as the primary filter
        $orders = Order::with([
            'requestOrder.sales',
            'customer',
            'items.barang',
            'requestOrder.items.barang'
        ])
        ->whereIn('status', ['completed', 'not_completed'])
        ->latest()
        ->get();

        $rows = collect();

        foreach ($orders as $order) {
            // Determine items: Priority to order items, fallback to request items
            $items = $order->items->isNotEmpty() ? $order->items : ($order->requestOrder ? $order->requestOrder->items : collect());
            
            foreach ($items as $item) {
                $rows->push((object)[
                    'order' => $order,
                    'ro'    => $order->requestOrder,
                    'item'  => $item
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'SO',
            'Nomor Penawaran',
            'PO',
            'Sales',
            'Customer',
            'Item',
            'Harga',
            'Quantity',
            'Subtotal',
            'Status',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '70AD47'],
                ],
            ],
        ];
    }

    /**
    * @param object $row
    */
    public function map($row): array
    {
        $this->rowNumber++;
        $order = $row->order;
        $ro = $row->ro;
        $item = $row->item;
        
        // Handle both OrderItem and RequestOrderItem types
        $itemName = $item instanceof OrderItem 
            ? $item->nama_barang 
            : ($item->barang ? $item->barang->nama_barang : ($item->nama_barang_custom ?? '-'));

        return [
            $this->rowNumber,
            $order->created_at ? $order->created_at->format('d/m/Y') : '-',
            $ro->sales_order_number ?? '-',
            $ro->nomor_penawaran ?? '-',
            $ro->no_po ?? ($order->no_po ?? '-'),
            $ro->sales?->name ?? ($order->sales?->name ?? 'N/A'),
            $order->customer?->nama_customer ?? ($order->customer_name ?? 'N/A'),
            $itemName,
            number_format($item->harga, 2, ',', '.'),
            $item->quantity,
            number_format($item->subtotal, 2, ',', '.'),
            $order->status === 'not_completed' ? 'Partial Delivery' : 
                ($order->status === 'completed' ? 'Selesai' : ucwords(str_replace('_', ' ', $order->status))),
        ];
    }
}
