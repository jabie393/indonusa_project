<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col bg-gradient-to-r from-[#225A97] to-[#0B1D31] items-center justify-between space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Daftar barang</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-4 py-3">Status Listing</th>
                        <th scope="col" class="px-4 py-3">Kode Barang</th>
                        <th scope="col" class="px-4 py-3">Nama Barang</th>
                        <th scope="col" class="px-4 py-3">Kategori</th>
                        <th scope="col" class="px-4 py-3">Stok</th>
                        <th scope="col" class="px-4 py-3">Satuan</th>
                        <th scope="col" class="px-4 py-3">Lokasi</th>
                        <th scope="col" class="px-4 py-3">Harga</th>
                        <th scope="col" class="px-4 py-3">Status Barang</th>
                        <th scope="col" class="px-4 py-3">Tipe Request</th>
                        <th scope="col" class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="h-min-[300px]">
                    @forelse ($barangs as $barang)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">{{ $barang->status_listing }}</td>
                            <td scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $barang->kode_barang }}
                            </td>
                            <td class="px-4 py-3">{{ $barang->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $barang->kategori }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }}</td>
                            <td class="px-4 py-3">{{ $barang->satuan }}</td>
                            <td class="px-4 py-3">{{ $barang->lokasi }}</td>
                            <td class="px-4 py-3">{{ $barang->harga }}</td>
                            <td class="px-4 py-3">{{ $barang->status_barang }}</td>
                            <td class="px-4 py-3">
                                @if($barang->tipe_request == 'primary')
                                    Barang baru
                                @elseif($barang->tipe_request == 'new_stock')
                                    Stok baru
                                @else
                                    {{ $barang->tipe_request }}
                                @endif
                            </td>
                            <td class="flex items-center justify-end px-4 py-3">

                                {{-- Approve barang --}}
                                <form action="{{ route('supply-orders.approve', $barang->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit"
                                        class="mb-2 me-2 rounded-lg bg-green-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        Approve
                                    </button>
                                </form>

                                {{-- Reject barang --}}
                                <button
                                    type="button"
                                    class="reject-btn mb-2 me-2 rounded-lg bg-red-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                    data-id="{{ $barang->id }}"
                                    data-nama="{{ $barang->nama_barang }}"
                                >
                                    Reject
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                Showing
                <span class="font-semibold text-gray-900 dark:text-white">{{ $barangs->firstItem() ?? 0 }}-{{ $barangs->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold text-gray-900 dark:text-white">{{ $barangs->total() ?? $barangs->count() }}</span>
            </span>
            <div>
                {{ $barangs->links() }}
            </div>
        </nav>
    </div>
    </div>

    </div>

        <!-- Modals -->
        @include('components.supply-orders-modal-reject')
        @vite(['resources/js/supply-orders.js'])

</x-app-layout>
