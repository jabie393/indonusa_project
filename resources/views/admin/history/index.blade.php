<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex items-center h-16 justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="px-4">
            {{-- Search --}}
            <form action="{{ route('history.index') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-64 md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" aria-controls="historyTable"
                        value="{{ request('search') }}"
                        class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                        placeholder="Search by kode, name, category, note or user" />
                </div>
            </form>
        </div>

    </div>

    <div
        class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table id="" data-order='[[0, "desc"]]'
                class="sortable hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead
                    class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-nowrap" data-sort-order="desc">Tanggal</th>
                        <th class="px-4 py-3 text-nowrap">Barang</th>
                        <th class="px-4 py-3 text-nowrap">Deskripsi</th>
                        <th class="px-4 py-3 text-nowrap">Stok</th>
                        <th class="px-4 py-3 text-nowrap">Perubahan Status</th>
                        <th class="px-4 py-3 text-nowrap">Diubah Oleh</th>
                        <th class="flex justify-center px-4 py-3 text-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse ($histories as $history)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 flex-shrink-0">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ ($history->changed_at ?? $history->created_at)->format('Y-m-d') }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ($history->changed_at ?? $history->created_at)->format('H:i:s') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 flex-shrink-0">
                                <div
                                    class="mb-0.5 text-[10px] font-semibold uppercase tracking-wider text-black dark:text-white">
                                    {{ $history->category }}
                                </div>
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ $history->goods_code }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $history->goods_name }}
                                </div>
                            </td>
                            <td class="px-4 max-w-xs align-middle">
                                <div class="max-w-[250px] break-words line-clamp-3">
                                    {{ $history->description }}
                                </div>
                            </td>
                            <td class="px-4 py-3 flex-shrink-0">
                                <span
                                    class="text-sm font-semibold text-gray-900 dark:text-white">{{ $history->stock }}</span>
                            </td>
                            <td class="px-4 py-3 flex-1">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full bg-yellow-100 dark:bg-yellow-900 px-3 py-1 text-xs font-medium text-yellow-700 dark:text-yellow-200">
                                        {{ ucfirst($history->old_status) }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-3 py-1 text-xs font-medium text-green-700 dark:text-green-200">
                                        {{ ucfirst($history->new_status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 flex-shrink-0">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if ($history->user)
                                        {{ $history->user->display_name }}
                                    @else
                                        {{ $history->changed_by }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center">
                                    <div
                                        class="group inline-flex overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700 transition-all duration-300 ease-in-out group-hover:w-auto">
                                        <a href="#"
                                            class="flex items-center justify-center whitespace-nowrap bg-blue-700 p-2 text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900 transition-all duration-300 ease-in-out">
                                            <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd"
                                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100 text-sm font-medium">Detail</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
            aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $histories->firstItem() ?? 0 }}-{{ $histories->lastItem() ?? 0 }}</span>
                    of
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $histories->total() ?? $histories->count() }}</span>
                </span>
                <form method="GET" action="{{ route('history.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()"
                        class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>{{ $size }}
                            </option>
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

    @vite(['resources/js/table-sort.js'])
</x-app-layout>