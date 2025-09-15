<x-front-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="mb-4 text-2xl font-bold">Keranjang</h1>
        @if (empty($keranjang))
            <p>Keranjang kosong.</p>
        @else
            <table class="w-full mb-4">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($keranjang as $id => $item)
                        @php $subtotal = $item['harga'] * $item['qty'];
                        $total += $subtotal; @endphp
                        <tr>
                            <td>{{ $item['nama'] }}</td>
                            <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('keranjang.kurangi', $id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">-</button>
                                </form>
                                <span class="mx-2">{{ $item['qty'] }}</span>
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                    <input type="hidden" name="harga" value="{{ $item['harga'] }}">
                                    <button type="submit"
                                        class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">+</button>
                                </form>
                            </td>
                            <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('keranjang.hapus', $id) }}" method="POST"
                                    onsubmit="return confirm('Hapus barang ini dari keranjang?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="font-bold mb-4">Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
            <form action="{{ route('keranjang.checkout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="inline-block rounded bg-green-600 px-6 py-2 text-white font-bold hover:bg-green-700">
                    Checkout
                </button>
            </form>
        @endif
    </div>
</x-front-layout>
