<x-app-layout>
<div class="container">
    <h2>Approved Orders (Sent to Warehouse)</h2>

    {{-- Notifikasi pesan sukses --}}
    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div> 
    @endif
    @if(session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div> 
    @endif

    <a href="{{ route('admin.incoming') }}" class="btn btn-secondary mb-3">Back to Incoming</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Order Number</th>
                <th>Kode Barang</th>
                <th>Sales</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Sent At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            @if($order->items && $order->items->count())
                @foreach($order->items as $item)
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{ $order->items->count() }}">{{ $loop->parent->iteration + ($orders->currentPage()-1) * $orders->perPage() }}</td>
                            <td rowspan="{{ $order->items->count() }}">{{ $order->order_number }}</td>
                            <td rowspan="{{ $order->items->count() }}">@if(optional($item->barang)->kode_barang){{ optional($item->barang)->kode_barang }}@else-@endif</td>
                            <td rowspan="{{ $order->items->count() }}">{{ optional($order->sales)->name ?? 'N/A' }}</td>
                            <td rowspan="{{ $order->items->count() }}">{{ $order->customer_name ?? '-' }}</td>
                        @endif

                        <td>{{ optional($item->barang)->nama_barang ?? 'Barang #' . $item->barang_id }}</td>
                        <td>{{ $item->quantity }}</td>

                        @if($loop->first)
                            <td rowspan="{{ $order->items->count() }}">{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : '-' }}</td>
                            <td rowspan="{{ $order->items->count() }}">{{ ucfirst($order->status) }}</td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>{{ $loop->iteration + ($orders->currentPage()-1) * $orders->perPage() }}</td>
                    <td>{{ $order->order_number }}</td>
                        <td>-</td>
                        <td>{{ optional($order->sales)->name ?? 'N/A' }}</td>
                    <td>{{ $order->customer_name ?? '-' }}</td>
                        <td>-</td>
                        <td>-</td>
                    <td>{{ $order->updated_at ? $order->updated_at->format('Y-m-d H:i') : '-' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endif
        @empty
            <tr><td colspan="8" class="text-center">Tidak ada approved orders.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
</x-app-layout>
