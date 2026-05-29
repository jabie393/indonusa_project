<x-app-layout>
    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('supervisor.history') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search dt-search-0" aria-controls="warehouseTable" value="{{ request('search') }}" class="dt-input block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div class="relative flex max-h-[calc(100vh-210px)] flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="shrink-0 flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">

        </div>
        <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
            <table class="sortable min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-gray-500 dark:text-gray-400">
                <thead class="sticky top-0 z-30 bg-gray-50 text-nowrap text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="text-nowrap px-4 py-3 text-left">Proses</th>
                        <th class="text-nowrap px-4 py-3 text-left">Nomor</th>
                        <th class="text-nowrap px-4 py-3 text-left">Customer/Barang</th>
                        <th class="text-nowrap px-4 py-3 text-left">Sales/Supervisor</th>
                        <th class="text-nowrap px-4 py-3 text-left">Grand Total</th>
                        <th class="text-nowrap px-4 py-3 text-left">Status</th>
                        <th class="text-nowrap px-4 py-3 text-left">Alasan</th>
                        <th class="text-nowrap px-4 py-3 text-left">Tanggal Approve/Reject</th>
                    </tr>
                </thead>
                <tbody class="text-nowrap">
                    @forelse($histories as $item)
                    <tr class="">
                        <td class="px-4 py-3">
                            @if ($item['type'] === 'request_order')
                                <span
                                    class="inline-flex items-center justify-center rounded-full border border-blue-200 bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 dark:border-blue-800/50 dark:bg-blue-950/30 dark:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send mr-1.5 shrink-0">
                                        <path d="m22 2-7 20-4-9-9-4Z" />
                                        <path d="M22 2 11 13" />
                                    </svg>
                                    Sent Penawaran
                                </span>
                            @elseif($item['type'] === 'custom_penawaran')
                                <span
                                    class="inline-flex items-center justify-center rounded-full border border-cyan-200 bg-cyan-50 px-2 py-1 text-xs font-semibold text-cyan-700 dark:border-cyan-800/50 dark:bg-cyan-950/30 dark:text-cyan-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text mr-1.5 shrink-0">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                        <path d="M10 9H8" />
                                        <path d="M16 13H8" />
                                        <path d="M16 17H8" />
                                    </svg>
                                    Custom Penawaran
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $item['number'] }}</td>
                        <td class="px-4 py-3">{{ $item['customer'] }}</td>
                        <td class="px-4 py-3">{{ $item['sales'] }}</td>
                        <td class="px-4 py-3">{{ $item['grand_total'] }}</td>
                        <td class="px-4 py-3">
                            @php
                                $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                $badgeText = 'text-gray-700 dark:text-gray-300';
                                $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';
                                $statusLabel = $item['status'];

                                if ($item['status'] === 'approved_supervisor' || $item['status'] === 'approved') {
                                    $statusLabel = 'Disetujui';
                                    $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                    $badgeText = 'text-green-700 dark:text-green-300';
                                    $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                } elseif ($item['status'] === 'rejected_supervisor' || $item['status'] === 'rejected') {
                                    $statusLabel = 'Ditolak';
                                    $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                    $badgeText = 'text-red-700 dark:text-red-300';
                                    $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                }
                            @endphp
                            <span
                                class="inline-flex items-center justify-center rounded-full px-2 py-1 text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                {!! $iconSvg !!}{{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $item['reason'] }}</td>
                        <td class="px-4 py-3">{{ $item['approved_at'] }}</td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        <nav class="sticky bottom-0 z-20 flex flex-col items-start justify-between space-y-3 bg-white p-4 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0" aria-label="Table navigation">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $histories->firstItem() ?? 0 }}-{{ $histories->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $histories->total() ?? $histories->count() }}</span>
                </span>
                <form method="GET" action="{{ route('supervisor.history') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select name="perPage" onchange="this.form.submit()" class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
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

    @vite(['resources/js/table-sort.js'])
</x-app-layout>
