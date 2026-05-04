<!DOCTYPE html>
<html lang="id-ID">

<head>
    <meta charset="utf-8">
    <title>Penawaran - Indonusa Jaya Bersama</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                     class="pointer-events-none absolute right-30 top-1/2 z-10 h-[563px] w-[563px] -translate-y-1/2 opacity-10" />
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

            <p class='mt-2 text-2xl text-[#2f5496]'>
                <strong>QUOTATION</strong>
            </p>

            <!-- INFO TABLE -->
            <div class="mt-8 text-[9pt]">
                <table class="w-full border-collapse">
                    <tbody>

                        <tr>
                            <td class="w-[75pt]"><strong>To</strong></td>
                            <td class="w-[170pt]">{{ $customPenawaran->to }}</td>
                            <td class=""><strong>Date</strong></td>
                            <td class="">{{ \Carbon\Carbon::parse($customPenawaran->date)->format('d/m/Y') }}</td>
                        </tr>

                        <tr>
                            <td class=""><strong>Up</strong></td>
                            <td class="">{{ $customPenawaran->up ?? '-' }}</td>
                            <td class=""><strong>Our Ref</strong></td>
                            <td class="">{{ $customPenawaran->our_ref }}</td>
                        </tr>

                        <tr>
                            <td class=""><strong>Subject</strong></td>
                            <td class="">{{ $customPenawaran->subject }}</td>
                            <td class="w-[75pt]"><strong>Email</strong></td>
                            <td class="w-[180pt]"><a href="mailto:{{ $customPenawaran->email }}">{{ $customPenawaran->email }}</a></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- INTRO TEXT -->
            <div class="mt-8 text-[9pt]">
                @if ($customPenawaran->intro_text)
                    <p class="whitespace-pre-wrap">{{ $customPenawaran->intro_text }}</p>
                @endif
            </div>

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
                            <th class="border border-black px-2 py-1 text-center text-white">Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customPenawaran->items as $index => $item)
                            <tr>
                                <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                                <td class="border px-2 py-1">{{ $item->nama_barang }}</td>
                                <td class="border px-2 py-1 text-center">{{ $item->qty }}</td>
                                <td class="border px-2 py-1 text-center">{{ $item->satuan }}</td>
                                <td class="border px-2 py-1">
                                    <div class="flex justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($item->harga, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="border px-2 py-1">
                                    <div class="flex justify-between">
                                        <span>Rp</span>
                                        <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="border px-2 py-1 text-center">
                                    @if ($item->images && count($item->images) > 0)
                                        <div class="flex flex-wrap justify-center gap-2">
                                            @foreach ($item->images as $image)
                                                @php
                                                    $imgBase64 = $getStorageImageBase64($image);
                                                @endphp

                                                @if ($imgBase64)
                                                    <img src="{{ $imgBase64 }}"
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
                                <td colspan="7"
                                    class="border px-2 py-1 text-center">Tidak ada barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TERMS -->
            <div class="mt-8 text-[9pt]">
                <p>Syarat dan ketentuan :</p>
                <ol class="ml-[26.08pt] mt-1 list-decimal space-y-0.5">
                    <li>Harga Franko On Site</li>
                    <li>Harga Sudah Include PPN 11%</li>
                    <li>Penawaran berlaku 2 Minggu</li>
                </ol>
            </div>

            <!-- SUMMARY -->
            <div class="ml-auto mt-8 w-[177.9pt] text-[9pt]">
                <table class="w-full border-collapse border border-black">
                    <tbody>
                        <tr>
                            <td class="border-b border-r border-black px-2">Sub Total</td>
                            <td class="border-b border-black px-2">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>{{ number_format($customPenawaran->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black px-2">Tax</td>
                            <td class="border-b border-black px-2">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>{{ number_format($customPenawaran->tax, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-blue-900">
                            <td class="border-b border-r border-black px-2 text-white">Grand Total</td>
                            <td class="border-black px-2 text-white">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>{{ number_format($customPenawaran->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SIGNATURE -->
            <div class="ml-auto mt-8 w-fit text-center text-[9pt]">
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

</body>

</html>
