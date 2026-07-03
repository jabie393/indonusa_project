<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi - {{ $no_kwitansi }}</title>
    <style>
        :root{
            --navy:#0b2b4a;      /* border & judul */
            --line:#0d4d4d;      /* garis header tipis */
            --gray-box:#e5e7eb;  /* box nominal abu-abu */
        }
        html,body{height:100%;}
        body {
            font-family: 'Times New Roman', serif;
            color:#000;
            margin:0;
            padding:30px 40px;
            background:#fff;
        }

        /* ===== HEADER ===== */
        .header{
            display:flex;
            align-items:center;
            gap:14px;
        }
        .logo{
            width:64px;
            height:64px;
            flex:0 0 auto;
        }
        .company-block{flex:1}
        .company-name{
            font-weight:800;
            font-size:19px;
            color:var(--navy);
            margin-bottom:4px;
        }
        .company-info{
            font-size:11px;
            color:#222;
            border-collapse:collapse;
        }
        .company-info td{padding:1px 4px 1px 0; vertical-align:top}
        .company-info td.k{white-space:nowrap; width:52px}
        .company-info td.sep{width:12px}

        .header-line{
            border:none;
            border-top:2px solid var(--navy);
            margin:14px 0 22px 0;
        }

        /* ===== OUTER NAVY FRAME (single) ===== */
        .frame{
            border:6px solid var(--navy);
            padding:22px 26px;
        }

        /* ===== TITLE (double border box) ===== */
        .title-box{
            display:flex;
            justify-content:center;
            margin-bottom:22px;
        }
        .title-inner{
            border:3px double #000;
            padding:6px 40px;
            text-align:center;
        }
        .title-inner h1{
            margin:0;
            font-size:26px;
            letter-spacing:3px;
            color:var(--navy);
            font-weight:700;
        }

        /* ===== NO. (single line, separate from meta table) ===== */
        .no-row{
            display:flex;
            gap:6px;
            font-style:italic;
            font-weight:700;
            margin-bottom:16px;
            border-bottom:1px solid #000;
            padding-bottom:4px;
        }
        .no-row .val{
            font-style:normal;
            font-weight:400;
        }

        /* ===== META TABLE ===== */
        .meta table{width:100%; border-collapse:collapse}
        .meta td{padding:9px 6px; vertical-align:top}
        .meta td.label{width:170px; font-style:italic; font-weight:700; white-space:nowrap}
        .meta td.sep{width:16px}
        .meta td.value{border-bottom:1px solid #000}
        .meta td.value strong{font-weight:700}
        .meta td.value em{font-style:italic}

        /* ===== SEJUMLAH / AMOUNT ROW ===== */
        .amount-row{
            display:flex;
            align-items:center;
            border-bottom:1px solid #000;
            padding:14px 6px 10px 6px;
            margin-top:4px;
        }
        .amount-row .label{
            font-style:italic;
            font-weight:700;
            width:170px;
            flex:0 0 auto;
        }
        .amount-row .sep{width:16px; flex:0 0 auto}
        .rp-box{
            background:#d1d5db;
            padding:6px 10px;
            font-weight:700;
            color:#111827;
            border-left:1px solid #999;
        }
        .amount-box{
            background:var(--gray-box);
            font-weight:700;
            padding:6px 14px;
            display:inline-block;
            min-width:120px;
            text-align:right;
        }

        /* ===== FOOTER / SIGNATURE ===== */
        .footer{
            display:flex;
            justify-content:flex-end;
            margin-top:70px;
        }
        .signature{
            text-align:center;
            min-width:220px;
        }
        .signature .place-date{margin-bottom:55px}
        .signature .name{font-weight:400}

        @media print{
            body{padding:14px 20px}
        }
    </style>
</head>
<body>

    <div class="header">
        <svg class="logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <circle cx="50" cy="50" r="46" fill="none" stroke="#0b2b4a" stroke-width="6"/>
            <rect x="30" y="30" width="30" height="30" fill="none" stroke="#0b2b4a" stroke-width="6"/>
            <rect x="42" y="42" width="30" height="30" fill="#0b2b4a"/>
        </svg>
        <div class="company-block">
            <div class="company-name">PT INDONUSA JAYA BERSAMA</div>
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

        <div class="title-box">
            <div class="title-inner"><h1>KUITANSI</h1></div>
        </div>

        <div class="no-row">
            <span>No.</span>
            <span class="val">{{ $no_kwitansi }}</span>
        </div>

        <div class="meta">
            <table>
                <tr>
                    <td class="label">Telah terima dari</td>
                    <td class="sep">:</td>
                    <td class="value"><strong>{{ $customerName }}</strong></td>
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

        <div class="amount-row">
            <div class="label">Sejumlah</div>
            <div class="sep">:</div>
            <div style="margin-left:auto; display:flex; align-items:center; gap:8px;">
                <div class="rp-box">Rp</div>
                <div class="amount-box">{{ number_format($amount, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="footer">
            <div class="signature">
                <div class="place-date">Surabaya, {{ $date }}</div>
                <div class="name">{{ $penerima ?? 'Alimul Imam S.AP' }}</div>
            </div>
        </div>

    </div>
</body>
</html>