<?php

namespace App\Exports;

use App\Models\Goods;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AllGoodsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting
{
    protected $search;
    protected $category;
    protected $stockStatus;

    public function __construct($search = null, $category = null, $stockStatus = null)
    {
        $this->search = $search;
        $this->category = $category;
        $this->stockStatus = $stockStatus;
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'I' => '_("Rp"* #,##0_);_("Rp"* (#,##0);_("Rp"* "-"_);_(@_)',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Kategori',
            'Nama Barang',
            'Deskripsi',
            'Stok',
            'Satuan',
            'Lokasi',
            'Harga Jual',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Goods::where('goods_status', 'approved');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('goods_name', 'like', "%{$this->search}%")
                  ->orWhere('goods_code', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->category) && $this->category !== 'all') {
            $query->where('category', $this->category);
        }

        if (!empty($this->stockStatus) && $this->stockStatus !== 'all') {
            if ($this->stockStatus === 'low') {
                $query->where('stock', '>', 0)->where('stock', '<=', 20);
            } elseif ($this->stockStatus === 'ready') {
                $query->where('stock', '>', 20);
            } elseif ($this->stockStatus === 'out') {
                $query->where('stock', '<=', 0);
            }
        }

        $results = $query->orderBy('goods_name', 'asc')->get();

        $rows = [];
        foreach ($results as $index => $barang) {
            $rows[] = [
                $index + 1,
                $barang->goods_code,
                $barang->category,
                $barang->goods_name,
                strip_tags($barang->description),
                $barang->stock,
                $barang->unit,
                $barang->location,
                $barang->selling_price,
            ];
        }

        return collect($rows);
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
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $table = new \PhpOffice\PhpSpreadsheet\Worksheet\Table(
                    "A1:I{$highestRow}",
                    'BarangTable'
                );
                $sheet->addTable($table);
            },
        ];
    }
}
