<x-app-layout>
<div class="container">
    <h2>Detail {{ $order->order_number }}</h2>
    <p><strong>Sales:</strong> {{ $order->sales->name ?? '-' }}</p>
    <p><strong>Customer:</strong> {{ $order->customer_name ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>

    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Barang</th>
          <th>Jumlah</th>
          <th>Status Item</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $k=>$it)
          <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $it->barang->nama_barang }}</td>
            <td>{{ $it->quantity }}</td>
            <td>{{ $it->status_item }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <a href="{{ route('requestorder.index') }}" class="btn btn-light">Kembali</a>
</div>
</x-app-layout>
