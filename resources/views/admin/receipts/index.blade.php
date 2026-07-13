<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>KUITANSI - {{ $no_receipt }}</title>
    <style>
        :root {
            --navy: #002d62;      /* Dark navy blue border & title */
        }
        body {
            font-family: 'Times New Roman', serif;
            color: #000;
            margin: 0;
            padding: 20px;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            padding-bottom: 8px;
        }
        .company-block {
            flex: 1;
        }
        .company-name {
            font-weight: bold;
            font-size: 26px;
            color: var(--navy);
            margin: 0;
            line-height: 1.1;
            letter-spacing: 0.5px;
        }
        .company-info {
            font-size: 11px;
            color: #000;
            font-weight: normal;
            margin-top: 4px;
            border-collapse: collapse;
        }
        .company-info td {
            padding: 1px 4px 1px 0;
            vertical-align: top;
        }
        .company-info td.k {
            white-space: nowrap;
            width: 45px;
        }
        .company-info td.sep {
            width: 12px;
            text-align: center;
        }

        /* Double line below header: one thick, one thin */
        .header-line-thick {
            border: none;
            border-top: 3.5px solid var(--navy);
            margin: 6px 0 2.5px 0;
        }
        .header-line-thin {
            border: none;
            border-top: 1px solid var(--navy);
            margin: 0 0 15px 0;
        }

        /* ===== OUTER FRAME ===== */
        .frame {
            border: 16px solid var(--navy);
            padding: 25px 30px;
            box-sizing: border-box;
            background: #fff;
        }

        /* ===== TITLE ===== */
        .title-box {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .title-inner {
            border: 3.5px double var(--navy);
            padding: 5px 85px;
            text-align: center;
        }
        .title-inner h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 5px;
            color: var(--navy);
            font-weight: bold;
        }

        /* ===== NO. ===== */
        .no-row {
            font-size: 15px;
            margin-bottom: 20px;
            display: inline-block;
            border-bottom: 1.5px solid #000;
            padding-bottom: 2.5px;
        }
        .no-label {
            font-style: italic;
            font-weight: bold;
            margin-right: 15px;
        }
        .no-val {
            font-weight: normal;
            letter-spacing: 0.5px;
        }

        /* ===== META TABLE ===== */
        .meta table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta td {
            padding: 10px 4px;
            vertical-align: bottom;
            font-size: 16px;
        }
        .meta td.label {
            width: 150px;
            font-style: italic;
            font-weight: bold;
            white-space: nowrap;
        }
        .meta td.value {
            border-bottom: 1.5px solid #000;
            padding-left: 8px;
        }
        .meta td.value em {
            font-style: italic;
        }

        /* ===== BOTTOM SECTION ===== */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 35px;
            padding-bottom: 10px;
        }
        
        /* Unified Sejumlah Box */
        .amount-box {
            display: inline-flex;
            border: 1.5px solid #000;
            font-family: 'Times New Roman', serif;
            box-shadow: 2px 2px 0px rgba(0,0,0,0.05);
        }
        .amount-label-cell {
            padding: 8px 24px;
            font-style: italic;
            font-weight: bold;
            background: #fff;
            border-right: 1.5px solid #000;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .amount-val-cell {
            background: #d9d9d9; /* Single light-medium gray background */
            padding: 8px 24px;
            font-weight: bold;
            font-style: italic;
            font-size: 17px;
            display: flex;
            align-items: center;
            min-width: 180px;
            justify-content: space-between;
        }
        .amount-rp {
            margin-right: 40px;
        }
        .amount-num {
            text-align: right;
        }

        /* Signature */
        .signature-container {
            text-align: center;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .date-row {
            border-bottom: 1.5px solid #000;
            padding-bottom: 2.5px;
            margin-bottom: 100px;
            display: inline-block;
            white-space: nowrap;
        }
        .date-text {
            font-size: 15px;
        }
        .signature-name {
            font-weight: normal;
            font-size: 15px;
            color: #333;
        }

        @media print {
            body {
                padding: 10px;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .frame {
                border-width: 16px;
            }
            @page {
                size: A4 landscape;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
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

    <div class="header">
        @if ($getPublicImageBase64('Logo_transparent.png'))
            <img src="{{ $getPublicImageBase64('Logo_transparent.png') }}" alt="Indonusa Jaya Bersama"
                 style="width: 80px; height: auto; margin-right: 20px; object-fit: contain; flex: 0 0 auto;" />
        @else
            <!-- Accurate SVG Logo from image -->
            <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width:72px; height:72px; margin-right:20px; flex:0 0 auto;">
                <!-- Circular background border -->
                <circle cx="50" cy="50" r="44" fill="none" stroke="#002d62" stroke-width="4.5"/>
                <!-- Two intersecting rounded rectangles/squares -->
                <rect x="34" y="32" width="26" height="26" rx="7" fill="none" stroke="#002d62" stroke-width="4.5"/>
                <rect x="44" y="42" width="26" height="26" rx="7" fill="none" stroke="#002d62" stroke-width="4.5"/>
                <!-- Vertical line on the left side of the rings -->
                <line x1="28" y1="22" x2="28" y2="78" stroke="#002d62" stroke-width="4.5" stroke-linecap="round"/>
            </svg>
        @endif
        <div class="company-block">
            <h1 class="company-name">PT INDONUSA JAYA BERSAMA</h1>
            <table class="company-info">
                <tr>
                    <td class="k">Alamat</td>
                    <td class="sep">|</td>
                    <td>Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296</td>
                </tr>
                <tr>
                    <td class="k">Telp</td>
                    <td class="sep">|</td>
                    <td>08121634173</td>
                </tr>
                <tr>
                    <td class="k">Fax</td>
                    <td class="sep">|</td>
                    <td>03187857885</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Double line under header -->
    <hr class="header-line-thick">
    <hr class="header-line-thin">

    <div class="frame">
        <div class="title-box">
            <div class="title-inner">
                <h1>KUITANSI</h1>
            </div>
        </div>

        <div class="no-row">
            <span class="no-label">No.</span>
            <span class="no-val">{{ $no_receipt }}</span>
        </div>

        <div class="meta">
            <table>
                <tr>
                    <td class="label">Telah terima dari</td>
                    <td class="value">:&nbsp;&nbsp;<strong>{{ strtoupper($customerName) }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Terbilang</td>
                    <td class="value">:&nbsp;&nbsp;<em>{{ $amount_words }}</em></td>
                </tr>
                <tr>
                    <td class="label">Untuk pembayaran</td>
                    <td class="value">:&nbsp;&nbsp;Sesuai Invoice Terlampir</td>
                </tr>
                <tr>
                    <td class="label">Rincian</td>
                    <td class="value">:&nbsp;&nbsp;Sesuai PO {{ $no_po }}</td>
                </tr>
            </table>
        </div>

        <div class="bottom-section">
            <div class="amount-box">
                <div class="amount-label-cell">Sejumlah</div>
                <div class="amount-val-cell">
                    <span class="amount-rp">Rp</span>
                    <span class="amount-num">{{ number_format($amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="signature-container">
                <div class="date-row">
                    <span class="date-text">Surabaya,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $date }}</span>
                </div>
                <div class="signature-name">{{ $penerima ?? 'Alimul Imam S.AP' }}</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            window.print();
            setTimeout(function () {
                window.close();
            }, 1000);
        });
    </script>
</body>
</html>
