<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('history.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-64 md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" aria-controls="historyTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search by kode, name, category, note or user" />
                </div>
            </form>
        </div>

    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol px-4 py-3"></th>
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
                            <td class="px-4 py-3"></td>
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
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="flex flex-col items-start justify-between space-y-3 p-4 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $histories->firstItem() ?? 0 }}-{{ $histories->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $histories->total() ?? $histories->count() }}</span>
                </span>
                <form method="GET" action="{{ route('history.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="ml-2 rounded border-gray-300 p-1 pl-2 pr-5 text-sm">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
            </div>
            <div>
                {{ $histories->links() }}
            </div>
        </nav>
    </div>
</x-app-layout>
