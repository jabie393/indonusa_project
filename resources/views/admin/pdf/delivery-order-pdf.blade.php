<!DOCTYPE html>
<html lang="id-ID">

<head>
    <meta charset="utf-8">
    <title>Penawaran - Indonusa Jaya Bersama</title>
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
    <div class="relative h-[29.7cm] w-full max-w-[21cm] overflow-hidden bg-white shadow-md print:m-0 print:h-[29.7cm] print:w-[21cm] print:shadow-none">

        <!-- INNER MARGINS (1.27 cm) -->
        <div class="relative h-full w-full p-[1.27cm] text-[11pt] leading-[1.08]"
             style="font-family: 'Tinos', serif;">

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

            <!-- WATERMARK IMAGE -->
            @if ($getPublicImageBase64('LogoText_transparent.png'))
                <img src="{{ $getPublicImageBase64('LogoText_transparent.png') }}"
                     alt=""
                     class="right-30 pointer-events-none absolute top-1/2 z-10 h-[563px] w-[563px] -translate-y-1/2 opacity-10" />
            @endif

            <!-- TOP HEADER LOGO -->

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

            <!-- INFO TABLE -->
            <div class="mt-2 text-[9pt]">
                <table class="w-full">
                    <tbody class="grid grid-cols-8">
                        <tr class="col-span-4 row-span-3 flex flex-col">
                            <td class="px-2">Delivery To :</td>
                            <td class="px-2">
                                <strong>{{ $orders->customer?->nama_customer ?? $orders->customer_name }}</strong>
                            </td>
                            <td class="px-2"><span class="text-2xs">{{ $orders->customer?->alamat_pengiriman ?? '-' }}</span></td>

                        </tr>
                        <tr class="col-span-1 row-span-3 flex flex-col">
                            <td class="flex justify-between px-2">Date <span>:</span></td>
                            <td class="flex justify-between px-2">DO No <span>:</span></td>
                            <td class="flex justify-between px-2">PO No <span>:</span></td>
                        </tr>

                        <tr class="col-span-3 row-span-3 flex flex-col">
                            <td><strong>{{ $orders->created_at->format('d F Y') }}</strong></td>
                            <td><strong>{{ $orders->do_number ?? $orders->order_number }}</strong></td>
                            <td><strong>{{ $orders->requestOrder?->no_po ?? '-' }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- ITEMS TABLE -->
            <div class="mt-8 text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <thead class="border border-black bg-blue-900">
                        <tr>
                            <th class="w-[25.05pt] border border-black px-2 py-1 text-center text-white">No</th>
                            <th class="w-[173.05pt] border border-black px-2 py-1 text-center text-white">Nama Barang</th>
                            <th class="w-[173.05pt] border border-black px-2 py-1 text-center text-white">Deskripsi</th>
                            <th class="w-[13.15pt] border border-black px-2 py-1 text-center text-white">Qty</th>
                            <th class="w-[81.3pt] border border-black px-2 py-1 text-center text-white">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $iteration = 1; @endphp
                        @foreach ($orders->items as $item)
                            @if ($item->delivered_quantity > 0)
                                <tr>
                                    <td class="border px-2 py-1 text-center">{{ $iteration++ }}</td>
                                    <td class="border px-2 py-1">{{ $item->nama_barang }}</td>
                                    <td class="border px-2 py-1 text-center">-UBAH-</td>
                                    <td class="border px-2 py-1 text-center">{{ $item->delivered_quantity }}</td>
                                    <td class="border px-2 py-1 text-center">-UBAH-</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- TERMS -->
            <div class="mt-8 text-[9pt]">
                <p>Goods and items cannot be refund except with aggrement. <br>Received the above in good order and
                    conditition.</p>
            </div>

            <!-- SIGNATURE -->
            <div class="mt-8 w-full text-[9pt]">
                <div class="grid grid-cols-3">
                    <div class="col-span-1">
                        <p>Received By,</p>
                    </div>
                    <div class="col-span-1"></div>
                    <div class="col-span-1">
                        <p>Shipped By,</p>
                    </div>
                </div>
                <div class="mt-25 grid grid-cols-3">
                    <div class="col-span-1 w-[200px] border-b border-black">
                    </div>
                    <div class="col-span-1">
                    </div>
                    <div class="col-span-1 w-[200px] border-b border-black">
                    </div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="col-span-1 w-[200px]">
                        <p>Company’s Stamp & Signature</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
