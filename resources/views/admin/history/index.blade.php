<x-app-layout>
    <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
            <div>
                <h2 class="mr-3 font-semibold text-white">Riwayat Perubahan Barang</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Kode Barang</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Status Lama</th>
                        <th class="px-4 py-3">Status Baru</th>
                        <th class="px-4 py-3">Diubah Oleh</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $history)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3">{{ $history->changed_at ?? $history->created_at }}</td>
                            <td class="px-4 py-3">{{ $history->kode_barang }}</td>
                            <td class="px-4 py-3">{{ $history->nama_barang }}</td>
                            <td class="px-4 py-3">{{ $history->kategori }}</td>
                            <td class="px-4 py-3">{{ $history->stok }}</td>
                            <td class="px-4 py-3">{{ $history->old_status }}</td>
                            <td class="px-4 py-3">{{ $history->new_status }}</td>
                            <td class="px-4 py-3">
                                @if ($history->user)
                                    {{ $history->user->display_name }}
                                @else
                                    {{ $history->changed_by }}
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $history->note }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada riwayat perubahan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
