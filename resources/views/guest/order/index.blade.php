<x-front-layout>
    <div class="duration-750 starting:opacity-0 flex min-h-[768px] w-full items-center justify-center bg-[#D9D9D9] opacity-100 transition-opacity lg:grow">

        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                <div>
                    <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Daftar Order</h2>
                </div>
            </div>
            @if ($barangs->isEmpty())
                <p class="text-gray-500">Belum ada barang yang bisa diorder.</p>
            @else
                <div class="mb-4 grid grid-cols-2 gap-4 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($barangs as $barang)
                        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <div class="h-56 w-full">
                                <a href="#">
                                    @if (!empty($barang->gambar))
                                        <img class="mx-auto h-full dark:hidden" src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" />
                                        <img class="mx-auto hidden h-16 dark:block" src="{{ url('files/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" />
                                    @else
                                        <img class="mx-auto h-full dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg" alt="" />
                                        <img class="mx-auto hidden h-16 dark:block" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front-dark.svg" alt="" />
                                    @endif
                                </a>
                            </div>
                            <div class="pt-6">

                                <a href="#" class="text-sm font-semibold leading-tight text-gray-900 hover:underline dark:text-white md:text-lg">{{ $barang->nama_barang }}</a>
                                <p class="text-md font-extrabold leading-tight text-gray-900 dark:text-white md:text-2xl">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                                <div class="mt-4 flex items-center justify-start gap-4">
                                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan ' . $barang->nama_barang . ' dengan harga Rp ' . number_format($barang->harga, 0, ',', '.') . '. Apakah masih tersedia?') }}"
                                        target="_blank"
                                        class="inline-flex h-full items-center rounded-lg bg-primary-700 px-3 py-2.5 text-xs font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 md:px-5">
                                        Pesan
                                    </a>
                                    <form action="{{ route('keranjang.tambah') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $barang->id }}">
                                        <input type="hidden" name="nama" value="{{ $barang->nama_barang }}">
                                        <input type="hidden" name="harga" value="{{ $barang->harga }}">
                                        <input type="hidden" name="gambar" value="{{ $barang->gambar }}">
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg bg-green-700 px-3 py-2 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 md:px-5">
                                            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</x-front-layout>
