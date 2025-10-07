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
                  <a href="{{ route('requestorder.show',$o) }}" class="btn btn-info btn-sm">Detail</a>
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
