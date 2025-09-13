<x-front-layout>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Order</h1>

        @if($barangs->isEmpty())
            <p class="text-gray-500">Belum ada barang yang bisa diorder.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($barangs as $barang)
                    <div class="bg-white shadow rounded-lg p-4">
                        <h2 class="text-lg font-semibold">{{ $barang->nama_barang }}</h2>
                        <p class="text-gray-700">Stock: {{ $barang->stok }}</p>
                        <p class="text-gray-900 font-bold">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        <a href="#"
                           class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Pesan
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-front-layout>
