<x-app-layout>
<div class="container">
    <h2>Daftar Request Barang</h2>
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('requestorder.create') }}" class="btn btn-primary mb-2">+ Request Baru</a>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>No Order</th>
          <th>Tanggal</th>
          <th>Customer</th>
          <th>Barang</th>
          <th>Jumlah</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
          @foreach($o->items as $i => $item)
            <tr>
              @if($i==0)
                <td rowspan="{{ $o->items->count() }}">{{ $o->order_number }}</td>
                <td rowspan="{{ $o->items->count() }}">{{ $o->created_at->format('Y-m-d H:i') }}</td>
                <td rowspan="{{ $o->items->count() }}">{{ $o->customer_name ?? '-' }}</td>
              @endif
              <td>{{ $item->barang->nama_barang ?? '-' }}</td>
              <td>{{ $item->quantity }}</td>
                @if($i==0)
                <td rowspan="{{ $o->items->count() }}">{{ $o->status }}</td>
                <td rowspan="{{ $o->items->count() }}">
                  {{-- Render a button that opens the modal and passes the order data as JSON in data-order --}}
                  @php
                    // prepare a lightweight order payload for the modal
                    $payload = [
                      'order_number' => $o->order_number,
                      'sales' => $o->sales ? ['name' => $o->sales->name] : null,
                      'customer_name' => $o->customer_name,
                      'status' => $o->status,
                      'items' => $o->items->map(function($it){
                        return [
                          'barang' => $it->barang ? ['nama_barang' => $it->barang->nama_barang] : null,
                          'quantity' => $it->quantity,
                          'status_item' => $it->status_item ?? null,
                        ];
                      })->toArray(),
                    ];
                    $json = e(json_encode($payload));
                  @endphp
                  <button type="button" class="btn btn-info btn-sm open-request-modal" data-order="{!! $json !!}">Detail</button>
                </td>
              @endif
            </tr>
          @endforeach
        @empty
          <tr><td colspan="7">Belum ada request.</td></tr>
        @endforelse
      </tbody>
    </table>

    {{ $orders->links() }}
</div>
</x-app-layout>

@include('components.request-order-modal')

@vite(['resources/js/requestorder-modal.js'])
