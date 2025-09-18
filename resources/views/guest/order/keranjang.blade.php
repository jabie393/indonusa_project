<x-front-layout>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center bg-[#D9D9D9] opacity-100 transition-opacity lg:grow">

        <div class="mx-auto w-full max-w-screen-xl px-4 2xl:px-0">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Keranjang</h2>
            @if (empty($keranjang))
                <p>Keranjang kosong.</p>
                <script>
                    setTimeout(function() {
                        window.location.href = "{{ route('order') }}";
                    });
                </script>
            @else
                <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                    <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-3xl">
                        <div class="space-y-6">
                            @php $total = 0; @endphp
                            @foreach ($keranjang as $id => $item)
                                @php
                                    $subtotal = $item['harga'] * $item['qty'];
                                    $total += $subtotal;
                                @endphp
                                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                                    <div class="space-y-4 md:flex md:items-center md:gap-6 md:space-y-0">
                                        <a href="#" class="md:order-0 shrink-0">
                                            @if (!empty($barang->gambar))
                                                <img class="h-20 w-20 dark:hidden" src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" />
                                                <img class="hidden h-20 w-20 dark:block" src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" />
                                            @else
                                                <img class="h-20 w-20 dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="" />
                                                <img class="hidden h-20 w-20 dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" alt="" />
                                            @endif
                                        </a>
                                        <div class="flex w-full justify-between">
                                            <div class="order-1 flex flex-col items-center justify-between md:order-3 md:flex-row md:justify-end">
                                                <label for="counter-input" class="sr-only">Choose quantity:</label>
                                                <div class="flex items-center">
                                                    <form action="{{ route('keranjang.kurangi', $id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">-</button>
                                                    </form>
                                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="mx-2 inline">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $id }}">
                                                        <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                                        <input type="hidden" name="harga" value="{{ $item['harga'] }}">
                                                        <input type="number" name="qty" value="{{ $item['qty'] }}" min="1"
                                                            class="w-16 rounded border border-gray-300 text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none"
                                                            onchange="this.form.submit()">
                                                    </form>
                                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $id }}">
                                                        <input type="hidden" name="nama" value="{{ $item['nama'] }}">
                                                        <input type="hidden" name="harga" value="{{ $item['harga'] }}">
                                                        <button type="submit"
                                                            class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100">+</button>
                                                    </form>
                                                </div>
                                                <div class="mt-5 text-end md:order-4 md:mt-0 md:w-32">
                                                    <p class="text-base font-bold text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                                </div>
                                            </div>

                                            <div class="order-0 flex w-full min-w-0 flex-1 flex-col justify-between space-y-4 md:order-2 md:max-w-md">
                                                <a href="#" class="text-base font-medium text-gray-900 hover:underline dark:text-white">{{ $item['nama'] }}</a>
                                                <div class="flex items-center gap-4">
                                                    <form action="{{ route('keranjang.hapus', $id) }}" method="POST" onsubmit="return confirm('Hapus barang ini dari keranjang?')">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline">
                                                            <svg class="me-1.5 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                        <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">Order summary</p>

                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Original price</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                                    </dl>
                                </div>

                                <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                    <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                    <dd class="text-base font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
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
                                <button type="submit"
                                    class="mt-5 flex w-full items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300">
                                    Checkout
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
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-front-layout>
