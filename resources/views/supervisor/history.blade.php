@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">History Penawaran</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No Penawaran</th>
                <th>Nama Customer</th>
                <th>Nama Sales</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Alasan Penolakan</th>
                <th>Tanggal Approve/Reject</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requestOrders as $ro)
            <tr>
                <td>{{ $ro->nomor_penawaran }}</td>
                <td>{{ $ro->customer_name }}</td>
                <td>{{ $ro->sales->name ?? '-' }}</td>
                <td>Rp {{ number_format($ro->grand_total, 2, ',', '.') }}</td>
                <td>
                    @if($ro->order && $ro->order->status === 'approved_supervisor')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($ro->order && $ro->order->status === 'rejected_supervisor')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </td>
                <td>{{ $ro->order->reason ?? '-' }}</td>
                <td>{{ $ro->order->approved_at ? $ro->order->approved_at->format('d-m-Y H:i') : '-' }}</td>
                <td>
                    <a href="{{ route('supervisor.request-order.show', $ro->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
