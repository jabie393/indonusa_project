<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>KUITANSI - {{ $no_receipt }}</title>
    <style>
        :root {
            --navy: #002060;      /* Dark navy blue border & title */
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
            padding-bottom: 10px;
        }
        .company-block {
            flex: 1;
        }
        .company-name {
            font-weight: bold;
            font-size: 26px;
            color: var(--navy);
            margin: 0;
            line-height: 1.2;
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

        .header-line {
            border: none;
            border-top: 3px solid var(--navy);
            margin: 6px 0 15px 0;
        }

        /* ===== OUTER FRAME ===== */
        .frame {
            border: 14px solid var(--navy);
            padding: 10px;
            box-sizing: border-box;
        }
        /* ===== INNER FRAME (double border) ===== */
        .inner-frame {
            border: 3px double #000;
            padding: 24px 30px;
            box-sizing: border-box;
        }

        /* ===== TITLE ===== */
        .title-box {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .title-inner {
            border: 3px double var(--navy);
            padding: 5px 65px;
            text-align: center;
        }
        .title-inner h1 {
            margin: 0;
            font-size: 26px;
            letter-spacing: 4px;
            color: var(--navy);
            font-weight: bold;
        }

        /* ===== NO. ===== */
        .no-row {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-end;
        }
        .no-label {
            font-style: italic;
            border-bottom: 1.5px solid #000;
            padding-bottom: 2px;
            width: 32px;
            display: inline-block;
        }
        .no-val {
            margin-left: 10px;
            font-weight: normal;
        }

        /* ===== META TABLE ===== */
        .meta table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .meta td {
            padding: 8px 4px;
            vertical-align: bottom;
            font-size: 15px;
        }
        .meta td.label {
            width: 160px;
            font-style: italic;
            font-weight: bold;
            white-space: nowrap;
        }
        .meta td.sep {
            width: 15px;
            text-align: center;
        }
        .meta td.value {
            border-bottom: 1.5px solid #000;
            padding-left: 8px;
        }

        /* ===== BOTTOM SECTION ===== */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
        }
        .amount-container {
            display: flex;
            align-items: center;
        }
        .amount-label {
            font-style: italic;
            font-weight: bold;
            font-size: 15px;
            margin-right: 15px;
        }
        .amount-box {
            display: inline-flex;
            align-items: stretch;
            border: 2px solid #000;
            background: #fff;
            overflow: hidden;
        }
        .rp-label {
            background: #a6a6a6; /* medium-dark gray */
            color: #000;
            font-weight: bold;
            padding: 6px 16px;
            border-right: 2px solid #000;
            font-size: 15px;
            display: flex;
            align-items: center;
        }
        .amount-value {
            background: #e6e6e6; /* light gray */
            color: #000;
            font-weight: bold;
            padding: 6px 20px;
            min-width: 140px;
            text-align: right;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .signature-container {
            text-align: center;
            min-width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .date-row {
            border-bottom: 1.5px solid #000;
            padding-bottom: 2px;
            margin-bottom: 45px;
            display: inline-block;
            white-space: nowrap;
        }
        .date-text {
            font-size: 14px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 14px;
        }

        @media print {
            body {
                padding: 10px;
            }
            .frame {
                border-width: 12px;
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
            <!-- Fallback SVG if logo image is not readable/found -->
            <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width:64px; height:64px; margin-right:20px; flex:0 0 auto;">
                <circle cx="50" cy="50" r="46" fill="none" stroke="#002060" stroke-width="6"/>
                <rect x="30" y="30" width="30" height="30" fill="none" stroke="#002060" stroke-width="6"/>
                <rect x="42" y="42" width="30" height="30" fill="#002060"/>
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

    <hr class="header-line">

    <div class="frame">
        <div class="inner-frame">
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
                        <td class="sep">:</td>
                        <td class="value"><strong>{{ strtoupper($customerName) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Terbilang</td>
                        <td class="sep">:</td>
                        <td class="value"><em>{{ $amount_words }}</em></td>
                    </tr>
                    <tr>
                        <td class="label">Untuk pembayaran</td>
                        <td class="sep">:</td>
                        <td class="value">Sesuai Invoice Terlampir</td>
                    </tr>
                    <tr>
                        <td class="label">Rincian</td>
                        <td class="sep">:</td>
                        <td class="value">Sesuai PO {{ $no_po }}</td>
                    </tr>
                </table>
            </div>

            <div class="bottom-section">
                <div class="amount-container">
                    <span class="amount-label">Sejumlah</span>
                    <div class="amount-box">
                        <span class="rp-label">Rp</span>
                        <span class="amount-value">{{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="signature-container">
                    <div class="date-row">
                        <span class="date-text">Surabaya,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $date }}</span>
                    </div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $penerima ?? 'Alimul Imam S.AP' }}</div>
                </div>
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
