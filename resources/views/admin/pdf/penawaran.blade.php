<!DOCTYPE html>
<html lang="id-ID">

    <head>
        <meta charset="utf-8">
        <title>Penawaran - Indonusa Jaya Bersama</title>
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
        <div class="relative h-[29.7cm] w-full max-w-[21cm] overflow-hidden bg-white shadow-md print:m-0 print:h-[29.7cm] print:w-[21cm] print:shadow-none">

            <!-- INNER MARGINS (1.27 cm) -->
            <div class="relative h-full w-full p-[1.27cm] font-sans text-[11pt] leading-[1.08]" style="font-family: Calibri, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">

                <!-- WATERMARK IMAGE -->
                <img src="{{ asset('images/LogoText_transparent.png') }}" alt=""
                    class="pointer-events-none absolute right-30 top-1/2 z-10 h-[563px] w-[563px] -translate-y-1/2 opacity-10" />

                <!-- TOP HEADER LOGO -->

                <!-- COMPANY INFO -->
                <div class="flex text-[9pt]">
                    <img src="{{ asset('images/Logo_transparent.png') }}" alt="Indonusa Jaya Bersama" class="w-[16%]" />

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
                                <td class="w-[184pt] border-b border-r border-black px-2">---</td>
                                <td class="w-[68pt] border-b border-r border-black px-2"><strong>Email</strong></td>
                                <td class="w-[177.3pt] border-b border-black px-2">---</td>
                            </tr>

                            <tr>
                                <td class="border-b border-r border-black px-2"><strong>Up</strong></td>
                                <td class="border-b border-r border-black px-2">---</td>
                                <td class="border-b border-r border-black px-2"><strong>Our Ref</strong></td>
                                <td class="border-b border-black px-2">---</td>
                            </tr>

                            <tr>
                                <td class="border-r border-black px-2"><strong>Subject</strong></td>
                                <td class="border-r border-black px-2">Penawaran ---</td>
                                <td class="border-r border-black px-2"><strong>Date</strong></td>
                                <td class="border-black px-2">---</td>
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
                <div class="mt-3 text-[9pt]">
                    <table class="w-full border-collapse border border-black">
                        <thead class="border border-black bg-blue-900">
                            <tr>
                                <th class="w-[25.05pt] border border-black px-2 py-1 text-center text-white">No</th>
                                <th class="w-[173.05pt] border border-black px-2 py-1 text-center text-white">Nama Barang
                                </th>
                                <th class="w-[13.15pt] border border-black px-2 py-1 text-center text-white">Qty</th>
                                <th class="w-[25pt] border border-black px-2 py-1 text-center text-white">Satuan</th>
                                <th class="w-[81.3pt] border border-black px-2 py-1 text-center text-white">Harga</th>
                                <th class="w-[67.15pt] border border-black px-2 py-1 text-center text-white">Total</th>
                                <th class="w-[92.05pt] border border-black px-2 py-1 text-center text-white">Gambar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-2 py-1 text-center">1</td>
                                <td class="border px-2 py-1 text-center">---</td>
                                <td class="border px-2 py-1 text-center">---</td>
                                <td class="border px-2 py-1 text-center">---</td>
                                <td class="border px-2 py-1 text-center">---</td>
                                <td class="border px-2 py-1 text-center">---</td>
                                <td class="border px-2 py-1 text-center">---</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- SUMMARY -->
                <div class="ml-auto mt-3 w-[177.9pt] text-[9pt]">
                    <table class="w-full border-collapse border border-black">
                        <tbody>
                            <tr>
                                <td class="border-b border-r border-black px-2">Sub Total</td>
                                <td class="border-b border-l border-black px-2">123123</td>
                            </tr>
                            <tr>
                                <td class="border-b border-r border-black px-2">Tax</td>
                                <td class="border-b border-l border-black px-2">---</td>
                            </tr>
                            <tr>
                                <td class="border-b border-r border-black px-2">Grand Total</td>
                                <td class="border-b border-l border-black px-2">---</td>
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
