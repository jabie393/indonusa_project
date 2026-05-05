<!DOCTYPE html>
<html lang="id-ID">

<head>
    <meta charset="utf-8">
    <title>Request Order - Indonusa Jaya Bersama</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400;1,700&display=swap');

        body {
            font-family: "Tinos", serif;
        }

        @media print {
            @page {
                size: A4;
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



        /* Page break indicator for browser preview */
        @media screen {
            .page-break-preview {
                width: 21cm;
                min-height: 29.7cm;
                background-image: linear-gradient(to bottom, #ffffff 29.68cm, #cbd5e1 29.68cm, #cbd5e1 29.7cm, #ffffff 29.7cm);
                background-size: 100% 29.7cm;
                position: relative;
                margin: 2rem auto;
            }

            .page-break-preview::after {
                content: "PAGE BREAK";
                position: absolute;
                top: 29.7cm;
                left: -60px;
                font-size: 8pt;
                color: #94a3b8;
                transform: rotate(-90deg);
                transform-origin: top left;
                pointer-events: none;
            }
        }
    </style>
</head>

<body class="m-0 flex flex-col items-center bg-slate-200 p-0 print:bg-white">

    @php
        // Helper function to get base64 encoded image from public/images
        $getPublicImageBase64 = function ($filename) {
            try {
                $path = public_path('images/' . $filename);
                if (file_exists($path) && is_readable($path)) {
                    $mime = mime_content_type($path);
                    $data = base64_encode(file_get_contents($path));
                    return 'data:' . $mime . ';base64,' . $data;
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
            return '';
        };

        // Helper function to get base64 encoded image from storage
        $getStorageImageBase64 = function ($imagePath) {
            try {
                if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                    return $imagePath;
                }

                $fullPath = str_starts_with($imagePath, 'public/') ? storage_path('app/public/' . ltrim(substr($imagePath, 7), '/')) : storage_path('app/public/' . ltrim($imagePath, '/'));

                if (file_exists($fullPath) && is_readable($fullPath)) {
                    $mime = mime_content_type($fullPath);
                    $data = base64_encode(file_get_contents($fullPath));
                    return 'data:' . $mime . ';base64,' . $data;
                }
            } catch (\Exception $e) {
                // Log error if needed
            }
            return '';
        };
    @endphp

    <!-- A4 PAGE WRAPPER -->
    <div class="page-break-preview bg-white shadow-md print:m-0 print:w-full print:shadow-none">

        <!-- INNER CONTENT -->
        <div class="content-wrapper z-1 relative w-full p-[1.27cm] text-[11pt] leading-[1.08] print:p-0"
             style="font-family: 'Tinos', serif;">

            <!-- WATERMARK IMAGE (OPTIONAL) -->
            @if ($getPublicImageBase64('LogoText_transparent.png'))
                <img src="{{ $getPublicImageBase64('LogoText_transparent.png') }}"
                     alt=""
                     class="pointer-events-none absolute left-1/2 top-1/2 z-10 h-[563px] w-[563px] -translate-x-1/2 -translate-y-1/2 opacity-10" />
            @endif

            <!-- COMPANY INFO -->
            <div class="flex text-[9pt]">
                @if ($getPublicImageBase64('Logo_transparent.png'))
                    <img src="{{ $getPublicImageBase64('Logo_transparent.png') }}"
                         alt="Indonusa Jaya Bersama"
                         class="w-[16%]" />
                @endif

                <div class="">
                    <div class="px-2 py-1">
                        <h1 class="text-3xl font-bold text-[#1f3864]">PT. INDONUSA JAYA BERSAMA</h1>
                    </div>
                    <div class="flex">
                        <div class="w-[35pt] border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Alamat</p>
                        </div>
                        <div class="flex flex-1 gap-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-[35pt] border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Telp</p>
                        </div>
                        <div class="flex flex-1 gap-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">08121634173</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-[35pt] border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Fax</p>
                        </div>
                        <div class="flex flex-1 gap-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">03187857885</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BLUE LINE -->
            <p class="mt-2 border-t-[4.5pt] border-[#2f5496] pt-1 text-[9pt]">
            </p>

            <p class='mt-2 text-2xl text-[#2f5496]'>
                <strong>QUATATION</strong>
            </p>

            <!-- INFO TABLE -->
            <div class="mt-8 text-[9pt]">
                <table class="w-full border-collapse">
                    <tbody>

                        <tr>
                            <td class="w-[75pt]">To</td>
                            <td class="w-[170pt]">{{ $requestOrder->customer_name }}</td>
                            <td class="">Date</td>
                            <td class="">{{ $requestOrder->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="">Up</td>
                            <td class="">{{ $requestOrder->sales->name ?? '-' }}</td>
                            <td class="">Our Ref</td>
                            <td class="">{{ $requestOrder->request_number }}</td>
                        </tr>
                        <tr>
                            <td class="">Subject</td>
                            <td class="">{{ $requestOrder->subject ?? 'Request Order - ' . $requestOrder->request_number }}</td>

                            <td class="w-[75pt]">Email</td>
                            <td class="w-[180pt]"><a href="mailto:{{ optional($requestOrder->sales)->email ?? '-' }}">{{ optional($requestOrder->sales)->email ?? '-' }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- INTRO TEXT: show only the editable note -->
            <div class="mt-8 text-[9pt]">
                <p class="whitespace-pre-wrap">{{ $requestOrder->catatan_customer }}</p>
            </div>

            <!-- SUPPORTING IMAGES -->
            @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                <div class="mt-3 text-[9pt]">
                    <h4 class="font-bold">Gambar Pendukung</h4>
                    <div class="mt-2 flex flex-wrap justify-start gap-2">
                        @foreach ($requestOrder->supporting_images as $image)
                            @php
                                $imgSrc = $getStorageImageBase64($image);
                            @endphp
                            @if ($imgSrc)
                                <div class="h-[90px] w-[90px] overflow-hidden border border-gray-300">
                                    <img src="{{ $imgSrc }}"
                                         alt="Gambar Pendukung"
                                         class="h-full w-full object-cover" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ITEMS TABLE -->
            <div class="mt-8 text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <thead class="border border-black bg-blue-900">
                        <tr>
                            <th class="w-[25.05pt] border border-black px-2 py-1 text-center text-white">No</th>
                            <th class="w-[160pt] border border-black px-2 py-1 text-center text-white">Nama Barang</th>
                            <th class="w-[13.15pt] border border-black px-2 py-1 text-center text-white">Qty</th>
                            <th class="w-[25pt] border border-black px-2 py-1 text-center text-white">Satuan</th>
                            <th class="w-[81.3pt] border border-black px-2 py-1 text-center text-white">Harga</th>
                            <th class="w-[67.15pt] border border-black px-2 py-1 text-center text-white">Total</th>
                            <th class="w-[92.05pt] border border-black px-2 py-1 text-center text-white">Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                            $totalPPN = 0;
                        @endphp
                        @forelse($requestOrder->items as $index => $item)
                            @php
                                $displayHarga = $item->harga ?? round(optional($item->barang)->harga * 1.3, 2);
                                $computedSubtotal = round($displayHarga * $item->quantity * (1 - ($item->diskon_percent ?? 0) / 100), 2);
                                $ppnAmount = round($computedSubtotal * (($item->ppn_percent ?? 0) / 100), 2);
                                $total += $computedSubtotal;
                                $totalPPN += $ppnAmount;
                            @endphp
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1 text-center">
                                    {{ optional($item->barang)->nama_barang ?? ($item->nama_barang_custom ?? '-') }}
                                </td>
                                <td class="border px-2 py-1 text-center">{{ $item->quantity }}</td>
                                <td class="border px-2 py-1 text-center">{{ optional($item->barang)->satuan ?? '-' }}
                                </td>
                                <td class="border px-2 py-1">
                                    <div class="flex justify-between">
                                        <span>
                                            Rp
                                        </span>
                                        <span>
                                            {{ number_format($displayHarga, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="border px-2 py-1">
                                    <div class="flex justify-between">
                                        <span>
                                            Rp
                                        </span>
                                        <span>
                                            {{ number_format($computedSubtotal, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    @php
                                        $itemImgs = $item->images ?? ($item->item_images ?? []);
                                    @endphp
                                    @if (!empty($itemImgs))
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @foreach ($itemImgs as $image)
                                                @php
                                                    $imgSrc = $getStorageImageBase64($image);
                                                @endphp
                                                @if ($imgSrc)
                                                    <img src="{{ $imgSrc }}"
                                                         alt="Gambar"
                                                         class="h-20 w-20 border border-gray-300 object-contain">
                                                @else
                                                    <span>-</span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="border px-2 py-1 text-center">Tidak ada barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="break-inside-avoid">



                <!-- TERMS -->
                <div class="mt-8 break-inside-avoid text-[9pt]">
                    <p>Syarat dan ketentuan :</p>
                    <ol class="ml-[26.08pt] mt-1 list-decimal space-y-0.5">
                        <li>Harga Franko On Site</li>
                        <li>Harga Sudah Include PPN 11%</li>
                        <li>Penawaran berlaku 2 Minggu</li>
                    </ol>
                </div>

                <!-- SUMMARY -->
                <div class="ml-auto mt-8 w-[177.9pt] break-inside-avoid text-[9pt]">
                    <table class="w-full border-collapse border border-black">
                        <tbody>
                            @php
                                $finalSubtotal = $requestOrder->subtotal ?? $total;
                                $finalTax = $requestOrder->tax ?? $totalPPN;
                                $finalGrandTotal = $requestOrder->grand_total ?? $total + $totalPPN;
                            @endphp
                            <tr>
                                <td class="border-b border-r border-black px-2">Sub Total</td>
                                <td class="border-b border-black px-2">
                                    <div class="flex justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($finalSubtotal, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border-b border-r border-black px-2">PPN</td>
                                <td class="border-b border-black px-2">
                                    <div class="flex justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($finalTax, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="bg-blue-900">
                                <td class="border-b border-r border-black px-2 text-white">Grand Total</td>
                                <td class="border-black px-2 text-white">
                                    <div class="flex justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($finalGrandTotal, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- SIGNATURE (Ensures block stays together and moves to next page if space is insufficient) -->
                <div class="ml-auto mt-8 w-fit text-center text-[9pt]"
                     style="page-break-inside: avoid; break-inside: avoid;">
                    <p>Hormat Kami</p>
                    <p>PT. INDONUSA JAYA BERSAMA</p>

                    <div class="mt-2">
                        @if ($getPublicImageBase64('ttd.png'))
                            <img src="{{ $getPublicImageBase64('ttd.png') }}"
                                 alt="Tanda tangan"
                                 class="mx-auto h-20 object-contain" />
                        @endif
                    </div>

                    <p class="mt-1">Alimul Imam S.AP</p>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
