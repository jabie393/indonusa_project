<table>
    <thead>
        <!-- Company Header (Kop Surat) -->
        <tr>
            <th colspan="9" style="font-size: 16px; font-weight: bold; text-align: center;">{{ strtoupper($company_name) }}</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 11px; text-align: center;">{{ $company_address }}</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 11px; text-align: center;">Telp: {{ $company_phone }} | Email: {{ $company_email }}</th>
        </tr>
        <tr>
            <th colspan="9" style="border-bottom: 2px solid #000000; height: 10px;"></th>
        </tr>
        <tr>
            <th colspan="9" style="height: 15px;"></th>
        </tr>

        <!-- Report Title -->
        <tr>
            <th colspan="9" style="font-size: 14px; font-weight: bold; text-align: center;">LAPORAN KINERJA SALES</th>
        </tr>
        <tr>
            <th colspan="9" style="font-size: 10px; text-align: center; color: #555555;">{{ $filter_description }}</th>
        </tr>
        <tr>
            <th colspan="9" style="height: 15px;"></th>
        </tr>

        <!-- Table Headings -->
        <tr style="background-color: #225A97; color: #ffffff;">
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; width: 5%;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; width: 12%;">Tanggal</th>
            <th style="font-weight: bold; border: 1px solid #000000; width: 18%;">No. Dokumen</th>
            <th style="font-weight: bold; border: 1px solid #000000; width: 15%;">Sales</th>
            <th style="font-weight: bold; border: 1px solid #000000; width: 20%;">Customer</th>
            <th style="font-weight: bold; border: 1px solid #000000; width: 25%;">Perihal</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right; width: 15%;">Total Nilai (Rp)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; width: 20%;">Status</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; width: 15%;">Tipe</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $totalAmount = 0; 
            $no = 1;
        @endphp
        @foreach($results as $row)
            @php
                $statusData = \App\Http\Controllers\Admin\SalesReportController::getStatusDetails($row->type, $row->order_status, $row->direct_status, $row->custom_quotation_id);
                $totalAmount += $row->grand_total;
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000000;">{{ $row->quotation_number ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $row->sales_name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $row->customer_name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $row->subject ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $row->grand_total }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $statusData['label'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $row->type }}</td>
            </tr>
        @endforeach

        <!-- Summary Row -->
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <td colspan="6" style="border: 1px solid #000000; text-align: right; font-weight: bold;">GRAND TOTAL</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold;">{{ $totalAmount }}</td>
            <td colspan="2" style="border: 1px solid #000000; background-color: #f2f2f2;"></td>
        </tr>

        <!-- Empty Rows before signature -->
        <tr><td colspan="9" style="height: 15px;"></td></tr>
        <tr><td colspan="9" style="height: 15px;"></td></tr>

        <!-- Signature Block -->
        <tr>
            <td colspan="6"></td>
            <td colspan="3" style="text-align: center; font-weight: bold;">
                Surabaya, {{ now()->format('d F Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="3" style="text-align: center; font-weight: bold;">
                {{ $leader_position }}
            </td>
        </tr>
        <!-- Space for Signature -->
        <tr><td colspan="9" style="height: 15px;"></td></tr>
        <tr><td colspan="9" style="height: 15px;"></td></tr>
        <tr><td colspan="9" style="height: 15px;"></td></tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">
                {{ $leader_name }}
            </td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="3" style="text-align: center; font-size: 9px; color: #555555;">
                Dicetak secara sistem pada: {{ now()->format('d/m/Y H:i:s') }}
            </td>
        </tr>
    </tbody>
</table>
