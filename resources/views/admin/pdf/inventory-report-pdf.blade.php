<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris Barang</title>
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
            <h2 class="text-xl font-bold text-slate-900 tracking-wide">LAPORAN INVENTARIS BARANG</h2>
            <p class="text-xs text-slate-600 mt-1 italic">{{ $filter_description }}</p>
        </div>

        <!-- Tabel Data -->
        <div class="w-full overflow-x-auto z-10 relative">
            <table class="w-full border-collapse border border-slate-400 text-[10px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-800">
                        <th class="border border-slate-400 p-2 text-center font-bold">No</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Kode Barang</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Kategori</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Nama Barang</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Deskripsi</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Stok</th>
                        <th class="border border-slate-400 p-2 text-center font-bold">Satuan</th>
                        <th class="border border-slate-400 p-2 text-left font-bold">Lokasi</th>
                        <th class="border border-slate-400 p-2 text-right font-bold">Harga Jual (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $index => $barang)
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-400 p-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-slate-400 p-2 font-bold">{{ $barang->goods_code }}</td>
                            <td class="border border-slate-400 p-2">{{ $barang->category }}</td>
                            <td class="border border-slate-400 p-2">{{ $barang->goods_name }}</td>
                            <td class="border border-slate-400 p-2 max-w-[200px] truncate">{{ strip_tags($barang->description) }}</td>
                            <td class="border border-slate-400 p-2 text-center font-bold">{{ $barang->stock }}</td>
                            <td class="border border-slate-400 p-2 text-center">{{ $barang->unit }}</td>
                            <td class="border border-slate-400 p-2">{{ $barang->location }}</td>
                            <td class="border border-slate-400 p-2 text-right font-bold">
                                Rp {{ number_format($barang->selling_price ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="border border-slate-400 p-8 text-center text-slate-500 font-bold">Tidak ada data inventaris barang ditemukan</td>
                        </tr>
                    @endforelse
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
