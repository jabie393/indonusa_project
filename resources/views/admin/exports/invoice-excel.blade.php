<table>
    {{-- Header --}}
    <tr>
        <td colspan="1" rowspan="4"></td> {{-- Space for Logo (placed by drawings()) --}}
        <td colspan="7" style="font-size: 24pt; font-weight: bold; color: #003366; font-family: 'Segoe UI', sans-serif;">
            PT INDONUSA JAYA BERSAMA
        </td>
    </tr>
    <tr>
        <td colspan="1" style="font-weight: bold; color: #003366;">Office</td>
        <td colspan="6" style="font-weight: bold; color: #003366;">Wonorejo Selatan VB No. 50 Rungkut, Surabaya - 60296</td>
    </tr>
    <tr>
        <td colspan="1" style="font-weight: bold; color: #003366;">Telp</td>
        <td colspan="6" style="font-weight: bold; color: #003366;">08121634173</td>
    </tr>
    <tr>
        <td colspan="1" style="font-weight: bold; color: #003366;">Fax</td>
        <td colspan="6" style="font-weight: bold; color: #003366;">03187857885</td>
    </tr>

    {{-- Blue Separator Line --}}
    <tr style="height: 10px;">
        <td colspan="9" style="border-top: 2px solid #2F5496;"></td>
    </tr>

    {{-- Invoice Title --}}
    <tr>
        <td colspan="4" style="font-size: 36pt; font-weight: bold; color: #003366; text-decoration: underline;">INVOICE</td>
        <td colspan="5"></td>
    </tr>

    <tr>
        <td colspan="9"></td>
    </tr>

    {{-- Invoice Details --}}
    <tr>
        <td colspan="1" style="font-weight: bold;">Invoice To :</td>
        <td colspan="4"></td>
        <td colspan="2" style="font-weight: bold; text-align: right;">Date :</td>
        <td colspan="2">{{ date('d M Y', strtotime($invDate)) }}</td>
    </tr>
    <tr>
        <td colspan="5" style="font-weight: bold;">{{ strtoupper($customerName) }}</td>
        <td colspan="2" style="font-weight: bold; text-align: right;">No Invoice :</td>
        <td colspan="2">{{ $invNumber }}</td>
    </tr>
    <tr>
        <td colspan="5" rowspan="2" style="vertical-align: top;">{{ $invAddress ?: '-' }}</td>
        <td colspan="2" style="font-weight: bold; text-align: right;">PO No :</td>
        <td colspan="2" style="text-align: left;">{{ $invPoNo }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="1" style="font-weight: bold;">NPWP :</td>
        <td colspan="8" style="text-align: left;">{{ $invNpwp }}</td>
    </tr>

    <tr>
        <td colspan="9" style="height: 20px;"></td>
    </tr>

    <thead>
        <tr style="height: 28px; vertical-align: middle; text-align: center; font-weight: bold; ">
            <th style="background-color: #002060; color: #ffffff; border: 1px solid #002060;">No</th>
            <th colspan="5" style="background-color: #002060; color: #ffffff; border: 1px solid #002060;">Description</th>
            <th style="background-color: #002060; color: #ffffff; border: 1px solid #002060;">Qty</th>
            <th style="background-color: #002060; color: #ffffff; border: 1px solid #002060;">Unit Price</th>
            <th style="background-color: #002060; color: #ffffff; border: 1px solid #002060;">Total</th>
        </tr>
    </thead>

    {{-- Items --}}
    <tbody>
        @foreach ($items as $i => $item)
            <tr style="height: 35px; vertical-align: middle;">
                <td style="text-align: center; border: 1px solid #ddd;">{{ $i + 1 }}</td>
                <td colspan="5" style="border: 1px solid #ddd;">{{ $item['nama_barang'] ?? ($item['description'] ?? '-') }}</td>
                <td style="text-align: center; border: 1px solid #ddd;">{{ $item['qty'] ?? ($item['quantity'] ?? 0) }}</td>
                <td style="text-align: right; border: 1px solid #ddd;">{{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                <td style="text-align: right; border: 1px solid #ddd;">{{ number_format($item['subtotal'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr style="height: 20px;">
            <td colspan="9" style="background-color: #002060; border: 1px solid #002060;"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td style="text-align: right; font-weight: bold;">Subtotal</td>
            <td style="text-align: right;">{{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td style="text-align: right; font-weight: bold;">DPP</td>
            <td style="text-align: right;">{{ number_format($dpp, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="7"></td>
            <td style="text-align: right; font-weight: bold;">PPN</td>
            <td style="text-align: right;">{{ number_format($tax, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #000;">
            <td colspan="7"></td>
            <td style="text-align: right; font-weight: bold;">Total</td>
            <td style="text-align: right; font-weight: bold;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td colspan="9" style="height: 15px;"></td>
        </tr>

        {{-- Footer --}}
        <tr>
            <td colspan="5" style="border-left: 1px solid #003366; border-top: 1px solid #003366; border-right: 1px solid #003366; font-weight: bold; color: #003366; padding-left: 10px;">PAYMENT INFORMATION :</td>
            <td colspan="1"></td>
            <td colspan="3" style="text-align: center; font-weight: bold;">PT. Indonusa Jaya Bersama</td>
        </tr>
        <tr>
            <td colspan="5" style="border-left: 1px solid #003366; border-right: 1px solid #003366; padding-left: 10px;">• BCA a/c. 7881213501</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" style="border-left: 1px solid #003366; border-right: 1px solid #003366; padding-left: 20px;">a/n. PT. Indonusa Jaya Bersama</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" style="border-left: 1px solid #003366; border-right: 1px solid #003366; padding-left: 20px;"></td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="5" style="border-left: 1px solid #003366; border-right: 1px solid #003366; border-bottom: 1px solid #003366; padding-left: 10px;">
                Thank you for your support.<br>
                We look forward to serve you again
            </td>
            <td colspan="1"></td>
            <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline; vertical-align: bottom;">
                <div style="height: 60px;"></div>
                Alimul Imam S.AP
            </td>
        </tr>
    </tfoot>
</table>
