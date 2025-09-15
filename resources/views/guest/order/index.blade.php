<x-front-layout>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center bg-white opacity-100 transition-opacity lg:grow">

        <div class="mx-auto max-w-7xl p-6">
            <h1 class="mb-4 text-2xl font-bold">Daftar Order</h1>

            @if ($barangs->isEmpty())
                <p class="text-gray-500">Belum ada barang yang bisa diorder.</p>
            @else
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    @foreach ($barangs as $barang)
                        <div class="rounded-lg bg-white p-4 shadow">
                            <h2 class="text-lg font-semibold">{{ $barang->nama_barang }}</h2>
                            <p class="font-bold text-gray-900">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                            <a
                                href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan ' . $barang->nama_barang . ' dengan harga Rp ' . number_format($barang->harga, 0, ',', '.') . '. Apakah masih tersedia?') }}"
                                target="_blank"
                                class="mt-2 inline-block rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                            >
                                Pesan
                            </a>
                            <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $barang->id }}">
                                <input type="hidden" name="nama" value="{{ $barang->nama_barang }}">
                                <input type="hidden" name="harga" value="{{ $barang->harga }}">
                                <button type="submit" class="mt-2 inline-block rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                    +
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-front-layout>
