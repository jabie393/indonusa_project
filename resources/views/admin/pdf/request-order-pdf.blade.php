<!DOCTYPE html>
<html lang="id-ID">

<head>
    <meta charset="utf-8">
    <title>Request Order - Indonusa Jaya Bersama</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            * {
                box-shadow: none !important;
            }

            body {
                background: #ffffff;
            }
        }
    </style>
</head>

<body class="m-0 flex justify-center bg-slate-200 p-0 print:bg-white">

    <!-- A4 PAGE WRAPPER (FIXED HEIGHT) -->
    <div
        class="relative h-[29.7cm] w-full max-w-[21cm] overflow-hidden bg-white shadow-md print:m-0 print:h-[29.7cm] print:w-[21cm] print:shadow-none">

        <!-- INNER MARGINS (1.27 cm) -->
        <div class="relative h-full w-full p-[1.27cm] font-sans text-[11pt] leading-[1.08]"
            style="font-family: Calibri, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">

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
                $getStorageImageBase64 = function ($imagePath) use (&$getStorageImageBase64) {
                    try {
                        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
                            return $imagePath;
                        }

                        $fullPath = str_starts_with($imagePath, 'public/')
                            ? storage_path('app/public/' . ltrim(substr($imagePath, 7), '/'))
                            : storage_path('app/public/' . ltrim($imagePath, '/'));

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

            <!-- WATERMARK IMAGE (OPTIONAL) -->
            @if ($getPublicImageBase64('LogoText_transparent.png'))
                <img src="{{ $getPublicImageBase64('LogoText_transparent.png') }}" alt=""
                    class="right-30 pointer-events-none absolute top-1/2 z-10 h-[563px] w-[563px] -translate-y-1/2 opacity-10" />
            @endif

            <!-- COMPANY INFO -->
            <div class="flex text-[9pt]">
                @if ($getPublicImageBase64('Logo_transparent.png'))
                    <img src="{{ $getPublicImageBase64('Logo_transparent.png') }}" alt="Indonusa Jaya Bersama"
                        class="w-[16%]" />
                @endif

                <div class="">
                    <div class="px-2 py-1">
                        <h1 class="text-3xl font-bold text-[#1f3864]">Indonusa jaya bersama</h1>
                    </div>
                    <div class="flex">
                        <div class="w-[38.6pt] border-r-2 border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Office</p>
                        </div>
                        <div class="flex-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-[38.6pt] border-r-2 border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Telp</p>
                        </div>
                        <div class="flex-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">08121634173</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-[38.6pt] border-r-2 border-[#1f3864] px-2 py-1">
                            <p class="font-bold text-[#1f3864]">Fax</p>
                        </div>
                        <div class="flex-1 px-2 py-1">
                            <p class="font-bold text-[#1f3864]">03187857885</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- BLUE LINE -->
            <p class="mt-2 border-t-[4.5pt] border-[#2f5496] pt-1 text-[9pt]">
                <span class="text-[#4472c4]">&nbsp;</span>
            </p>

            <!-- INFO TABLE -->
            <div class="mt-2 text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <tbody>

                        <tr>
                            <td class="w-[68.1pt] border-b border-r border-black px-2"><strong>To</strong></td>
                            <td class="w-[184pt] border-b border-r border-black px-2">{{ $requestOrder->customer_name }}
                            </td>
                            <td class="w-[68pt] border-b border-r border-black px-2"><strong>Email</strong></td>
                            <td class="w-[177.3pt] border-b border-black px-2">
                                {{ optional($requestOrder->sales)->email ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="border-b border-r border-black px-2"><strong>Up</strong></td>
                            <td class="border-b border-r border-black px-2">{{ $requestOrder->sales->name ?? '-' }}</td>
                            <td class="border-b border-r border-black px-2"><strong>Our Ref</strong></td>
                            <td class="border-b border-black px-2">{{ $requestOrder->request_number }}</td>
                        </tr>

                        <tr>
                            <td class="border-r border-black px-2"><strong>Subject</strong></td>
                            <td class="border-r border-black px-2">
                                {{ $requestOrder->subject ?? 'Request Order - ' . $requestOrder->request_number }}</td>
                            <td class="border-r border-black px-2"><strong>Date</strong></td>
                            <td class="border-black px-2">{{ $requestOrder->created_at->format('d/m/Y') }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- INTRO TEXT: show salutation then the editable note -->
            <div class="mt-2 text-[9pt]">
                <p class="border-b border-black pb-1">Dengan Hormat,</p>
                <p class="mt-1">&nbsp;</p>
                @php
                    $noteToShow =
                        $pdfNote ??
                        ($requestOrder->catatan_customer ??
                            'Untuk memenuhi kebutuhan..., bersama ini kami sampaikan penawaran harga beserta spesifikasi produk sebagai berikut:');
                @endphp
                <p>{!! nl2br(e($noteToShow)) !!}</p>
            </div>

            <!-- SUPPORTING IMAGES -->
            @if ($requestOrder->supporting_images && count($requestOrder->supporting_images) > 0)
                <div class="mt-3 text-[9pt]">
                    <h4 class="font-bold">Gambar Pendukung</h4>
                    <div class="mt-2 flex flex-wrap justify-start gap-2">
                        @foreach ($requestOrder->supporting_images as $image)
                            @php
                                $imgSrc = getStorageImageBase64($image);
                            @endphp
                            @if ($imgSrc)
                                <div class="h-[90px] w-[90px] overflow-hidden border border-gray-300">
                                    <img src="{{ $imgSrc }}" alt="Gambar Pendukung"
                                        class="h-full w-full object-cover" />
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- ITEMS TABLE -->
            <div class="mt-3 text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <thead class="border border-black bg-blue-900">
                        <tr>
                            <th class="w-[25.05pt] border border-black px-2 py-1 text-center text-white">No</th>
                            <th class="w-[173.05pt] border border-black px-2 py-1 text-center text-white">Nama Barang
                            </th>
                            <th class="w-[13.15pt] border border-black px-2 py-1 text-center text-white">Qty</th>
                            <th class="w-[30.15pt] border border-black px-2 py-1 text-center text-white">Diskon</th>
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
                                $computedSubtotal = round(
                                    $displayHarga * $item->quantity * (1 - ($item->diskon_percent ?? 0) / 100),
                                    2,
                                );
                                $ppnAmount = round($computedSubtotal * (($item->ppn_percent ?? 0) / 100), 2);
                                $total += $computedSubtotal;
                                $totalPPN += $ppnAmount;
                            @endphp
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1">
                                    {{ optional($item->barang)->nama_barang ?? ($item->barang->nama_barang ?? $item->barang_id) }}
                                </td>
                                <td class="border px-2 py-1 text-center">{{ $item->quantity }}</td>
                                <td class="border px-2 py-1 text-center">{{ $item->diskon_percent ?? 0 }}%</td>
                                <td class="border px-2 py-1 text-center">{{ optional($item->barang)->satuan ?? '-' }}
                                </td>
                                <td class="border px-2 py-1 text-right">Rp
                                    {{ number_format($displayHarga, 2, ',', '.') }}</td>
                                <td class="border px-2 py-1 text-right">Rp
                                    {{ number_format($computedSubtotal, 2, ',', '.') }}</td>
                                <td class="border px-2 py-1 text-center">
                                    @if ($item->item_images && count($item->item_images) > 0)
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @foreach ($item->item_images as $image)
                                                @php
                                                    $imgSrc = getStorageImageBase64($image);
                                                @endphp

                                                @if ($imgSrc)
                                                    <img src="{{ $imgSrc }}" alt="Gambar"
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
                                <td colspan="8" class="border px-2 py-1 text-center">Tidak ada barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- SUMMARY -->
            <div class="ml-auto mt-3 w-[177.9pt] text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <tbody>
                        @php
                            $finalSubtotal = $requestOrder->subtotal ?? $total;
                            $finalTax = $requestOrder->tax ?? $totalPPN;
                            $finalGrandTotal = $requestOrder->grand_total ?? $total + $totalPPN;
                        @endphp
                        <tr>
                            <td class="border-b border-r border-black px-2">Sub Total</td>
                            <td class="border-b border-l border-black px-2 text-right">Rp
                                {{ number_format($finalSubtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black px-2">Tax</td>
                            <td class="border-b border-l border-black px-2 text-right">Rp
                                {{ number_format($finalTax, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black px-2">Grand Total</td>
                            <td class="border-b border-l border-black px-2 text-right font-bold">Rp
                                {{ number_format($finalGrandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- TERMS -->
            <div class="mt-3 text-[9pt]">
                <p>Syarat dan ketentuan :</p>
                <ol class="ml-[26.08pt] mt-1 list-decimal space-y-0.5">
                    <li>Harga Customer On Site</li>
                    <li>Proses pengerjaan 45 Hari kerja</li>
                    <li>Penawaran berlaku 14 Hari setelah diterbitkan Penawaran Harga ini.</li>
                </ol>
            </div>

            <!-- SIGNATURE -->
            <div class="ml-auto mt-8 w-fit text-center text-[9pt]">
                <p>Hormat Kami</p>
                <p>Indonusa Jaya Bersama</p>

                <div class="mt-2">
                    <img src="{{ asset('images/ttd.png') }}" alt="Tanda tangan" class="mx-auto h-20 object-contain" />
                </div>

                <p class="mt-1">Alimul Imam S.AP</p>
            </div>

        </div>
    </div>

</body>

</html>
