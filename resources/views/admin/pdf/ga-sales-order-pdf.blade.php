<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Sales Order Invoice & Kuitansi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap');
        body {
            font-family: "Tinos", serif;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 1.27cm;
            }
            * {
                box-shadow: none !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            body {
                background: #ffffff;
                margin: 0;
            }
        }
    </style>
</head>
<body class="bg-white text-black text-xs p-4">
    @php
        $getPublicImageBase64 = function ($filename) {
            try {
                $path = public_path('images/' . $filename);
                if (file_exists($path) && is_readable($path)) {
                    $mime = mime_content_type($path);
                    $data = base64_encode(file_get_contents($path));
                    return 'data:' . $mime . ';base64,' . $data;
                }
            } catch (\Exception $e) {}
            return '';
        };
    @endphp

    <!-- Container -->
    <div class="relative w-full">
        <!-- Kop Surat -->
        <div class="flex items-center gap-4 border-b-4 border-[#2f5496] pb-3 mb-6">
            @if ($getPublicImageBase64('Logo_transparent.png'))
                <img src="{{ $getPublicImageBase64('Logo_transparent.png') }}" alt="Logo" class="w-[80px] h-auto object-contain" />
            @endif
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-[#1f3864] leading-tight">{{ strtoupper($company_name) }}</h1>
                <p class="text-xs text-slate-700 font-bold mt-1">{{ $company_address }}</p>
                <p class="text-xs text-slate-600 font-bold">Telp: {{ $company_phone }} | Email: {{ $company_email }}</p>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-slate-900 tracking-wide">LAPORAN SALES ORDER INVOICE & KUITANSI</h2>
            <p class="text-xs text-slate-600 mt-1 italic">{{ $filter_description }}</p>
        </div>

        <!-- Tabel Data -->
        <div class="w-full overflow-x-auto z-10 relative">
            <table class="w-full border-collapse border border-slate-400 text-[10px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-800">
                        <th class="border border-slate-400 p-2 text-center font-bold">No</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Tanggal</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Customer</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Request Order</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Nomor Quotation</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">No Invoice</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">No Kwitansi</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">DO</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">PO</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Sales Order</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Jumlah Item</th>
                        <th class="border border-slate-400 p-2 text-right font-bold">Total (Rp)</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Diskon %</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Status</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Berlaku Sampai</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @forelse($results as $index => $row)
                        @php
                            $grandTotal += $row->grand_total ?? 0;
                            $berlakuSampai = '-';
                            if ($row->valid_date) {
                                $berlakuSampai = \Carbon\Carbon::parse($row->valid_date)->translatedFormat('d F Y');
                            } elseif ($row->expired_at) {
                                $berlakuSampai = \Carbon\Carbon::parse($row->expired_at)->translatedFormat('d F Y');
                            }
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-400 p-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-slate-400 p-2 text-center">{{ $row->required_date ? $row->required_date->format('d/m/Y') : '-' }}</td>
                            <td class="border border-slate-400 p-2 font-bold">{{ $row->customer_name ?? '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->request_number ?? '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->quotation_number ?? '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->order?->batches->pluck('no_invoice')->filter()->implode(', ') ?: '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->order?->batches->pluck('no_receipt')->filter()->implode(', ') ?: '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->order?->batches->pluck('do_number')->filter()->implode(', ') ?: '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->no_po ?? '-' }}</td>
                            <td class="border border-slate-400 p-2">{{ $row->sales_order_number ?? '-' }}</td>
                            <td class="border border-slate-400 p-2 text-center">{{ $row->items->count() }}</td>
                            <td class="border border-slate-400 p-2 text-right font-bold">
                                Rp {{ number_format($row->grand_total ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="border border-slate-400 p-2 text-center">
                                {{ ($row->items && $row->items->count() > 0) ? ($row->items->first()->discount_percent ?? 0) . '%' : '0%' }}
                            </td>
                            <td class="border border-slate-400 p-2 text-center font-medium">{{ $row->status }}</td>
                            <td class="border border-slate-400 p-2 text-center">{{ $berlakuSampai }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15" class="border border-slate-400 p-8 text-center text-slate-500 font-bold">Tidak ada data transaksi ditemukan</td>
                        </tr>
                    @endforelse

                    <!-- Grand Total Row -->
                    @if($results->isNotEmpty())
                        <tr class="bg-slate-100 font-bold">
                            <td colspan="11" class="border border-slate-400 p-2 text-right font-bold">GRAND TOTAL</td>
                            <td class="border border-slate-400 p-2 text-right font-bold text-[#1f3864]">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td colspan="3" class="border border-slate-400 p-2"></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Tanda Tangan -->
        <div class="mt-12 flex justify-end text-xs" style="page-break-inside: avoid; break-inside: avoid;">
            <div class="text-center w-[200px]">
                <p class="font-bold">Surabaya, {{ now()->format('d F Y') }}</p>
                <p class="font-bold mb-16">{{ $leader_position }}</p>
                
                @if ($getPublicImageBase64('ttd.png'))
                    <img src="{{ $getPublicImageBase64('ttd.png') }}" alt="Signature" class="mx-auto h-[60px] object-contain -mt-12 mb-4 relative z-0" />
                @endif
                
                <p class="font-bold underline text-slate-900">{{ $leader_name }}</p>
                <p class="text-[9px] text-slate-500 mt-1 italic">Dicetak secara sistem pada: {{ $print_date }}</p>
            </div>
        </div>
    </div>
</body>
</html>
