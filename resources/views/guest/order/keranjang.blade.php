<x-front-layout>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center bg-[#D9D9D9] opacity-100 transition-opacity lg:grow">

        <div class="mx-auto max-w-2xl p-6">
            <h1 class="mb-4 text-2xl font-bold">Keranjang</h1>
            @if (empty($keranjang))
                <p>Keranjang kosong.</p>
            @else
                <table class="mb-4 w-full">
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
                            @php
                                $subtotal = $item['harga'] * $item['qty'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $item['nama'] }}</td>
                                <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('keranjang.kurangi', $id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="rounded bg-yellow-500 px-2 py-1 text-white hover:bg-yellow-600">-</button>
                                    </form>
                                    <span class="mx-2">{{ $item['qty'] }}</span>
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                        <input type="hidden" name="harga" value="{{ $item['harga'] }}">
                                        <button type="submit" class="rounded bg-green-500 px-2 py-1 text-white hover:bg-green-600">+</button>
                                    </form>
                                </td>
                                <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('keranjang.hapus', $id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?')">
                                        @csrf
                                        <button type="submit" class="rounded bg-red-600 px-2 py-1 text-white hover:bg-red-700">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mb-4 font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</div>
                @php
                    $waNumber = '6281234567890'; // WA admin
                    $pesan = "Halo, saya ingin memesan:\n";
                    foreach ($keranjang as $item) {
                        $pesan .= "- {$item['nama']} (Qty: {$item['qty']}) @ Rp " . number_format($item['harga'], 0, ',', '.') . "\n";
                    }
                    $pesan .= 'Total: Rp ' . number_format($total, 0, ',', '.');
                    $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($pesan);
                @endphp
                <form action="{{ route('keranjang.checkout') }}" method="POST" target="_blank" style="display:inline;">
                    @csrf
                    <input type="hidden" name="waUrl" value="{{ $waUrl }}">
                    <button type="submit" class="inline-block rounded bg-green-600 px-6 py-2 font-bold text-white hover:bg-green-700">
                        Checkout via WhatsApp
                    </button>
                </form>
                <script>
                    // Reload halaman dan redirect ke order
                    document.querySelector('form[action="{{ route('keranjang.checkout') }}"]').addEventListener('submit', function() {
                        setTimeout(function() {
                            window.location.href = "{{ route('order') }}";
                        }, 500);
                    });
                </script>
            @endif
        </div>
    </div>
</x-front-layout>
