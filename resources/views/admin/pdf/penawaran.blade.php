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

        /* Footer styling for the last page only */
        .footer-logo {
            margin-top: 2rem;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        @media print {
            .footer-logo {
                position: absolute;
                bottom: 0;
                left: 0;
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
    @endphp

    <!-- A4 PAGE WRAPPER -->
    <div class="page-break-preview bg-white shadow-md print:m-0 print:w-full print:shadow-none">

        <!-- INNER CONTENT -->
        <div class="content-wrapper relative h-full w-full p-[1.27cm] text-[11pt] leading-[1.08] z-1 print:p-0"
             style="font-family: 'Tinos', serif;">

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
                            <td class="w-[75pt]">To</td>
                            <td class="w-[170pt]">---</td>
                            <td class="">Date</td>
                            <td class="">---</td>
                        </tr>

                        <tr>
                            <td class="">Up</td>
                            <td class="">---</td>
                            <td class="">Our Ref</td>
                            <td class="">---</td>
                        </tr>

                        <tr>
                            <td class="">Subject</td>
                            <td class="">Penawaran ---</td>
                            <td class="w-[75pt]">Email</td>
                            <td class="w-[180pt]">---</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <!-- INTRO TEXT -->
            <div class="mt-2 text-[9pt]">
                <p class="border-b border-black pb-1">Dengan Hormat,</p>
                <p class="mt-1">&nbsp;</p>
                <p>Untuk memenuhi Kebutuhan --- ---,</p>
                <p class="mt-1">Dengan ini kami mengajukan penawaran harga sebagai berikut :</p>
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
                        <tr>
                            <td class="border px-2 py-1 text-center">1</td>
                            <td class="border px-2 py-1">---</td>
                            <td class="border px-2 py-1 text-center">---</td>
                            <td class="border px-2 py-1 text-center">---</td>
                            <td class="border px-2 py-1">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>---</span>
                                </div>
                            </td>
                            <td class="border px-2 py-1">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>---</span>
                                </div>
                            </td>
                            <td class="border px-2 py-1 text-center">---</td>
                        </tr>
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
                            <td class="border-b border-black px-2 text-right">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>123123</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black px-2">PPN</td>
                            <td class="border-b border-black px-2 text-right">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>---</span>
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-blue-900">
                            <td class="border-b border-r border-black px-2 text-white">Grand Total</td>
                            <td class="border-black px-2 text-right text-white">
                                <div class="flex justify-between">
                                    <span>Rp</span>
                                    <span>---</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SIGNATURE (Ensures block stays together and moves to next page if space is insufficient) -->
            <div class="ml-auto mt-8 w-fit text-center text-[9pt]" style="page-break-inside: avoid; break-inside: avoid;">
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

            <!-- FOOTER LOGO (Last page only) -->
            <div class="footer-logo pointer-events-none mt-10">
                <img src="{{ $getPublicImageBase64('footer_logo.png') }}"
                     alt="Footer"
                     class="mx-auto h-20 object-contain" />
            </div>

        </div>

    </body>

</html>
