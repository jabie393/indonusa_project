@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Sent Penawaran</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No Penawaran</th>
                <th>Nama Customer</th>
                <th>Nama Sales</th>
                <th>Total Diskon</th>
                <th>Grand Total</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requestOrders as $ro)
            <tr>
                <td>{{ $ro->nomor_penawaran }}</td>
                <td>{{ $ro->customer_name }}</td>
                <td>{{ $ro->sales->name ?? '-' }}</td>
                <td>{{ $ro->items->max('diskon_percent') }}%</td>
                <td>Rp {{ number_format($ro->grand_total, 2, ',', '.') }}</td>
                <td>{{ $ro->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <form action="{{ route('supervisor.approve', $ro->id) }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Terima</button>
                    </form>
                    <button type="button" class="btn btn-danger btn-sm" onclick="konfirmasiTolak({{ $ro->id }}, '{{ $ro->nomor_penawaran }}')">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                    <form id="formTolak{{ $ro->id }}" action="{{ route('supervisor.reject', $ro->id) }}" method="POST" style="display:none">
                        @csrf
                        <input type="hidden" name="reason" id="reasonInput{{ $ro->id }}">
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection




@push('scripts')
<script>
function konfirmasiTolak(id, noPenawaran) {
    let alasan = '';
    while (true) {
        alasan = prompt('Masukkan alasan penolakan untuk penawaran ' + noPenawaran + ':', alasan);
        if (alasan === null) return; // Cancel
        if (alasan.trim() !== '') break;
        alert('Alasan penolakan wajib diisi!');
    }
    document.getElementById('reasonInput' + id).value = alasan;
    document.getElementById('formTolak' + id).submit();
}
</script>
@endpush
