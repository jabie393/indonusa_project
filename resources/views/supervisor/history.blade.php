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

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex items-center justify-between bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">

        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="selectCol px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300"></th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Proses</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nomor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Customer/Barang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Sales/Supervisor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Grand Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Alasan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tanggal Approve/Reject</th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse($histories as $item)
                        <tr class="">
                            <td class="selectCol px-4 py-3"></td>
                            <td class="px-4 py-3">
                                @if ($item['type'] === 'request_order')
                                    <span class="inline-block rounded bg-blue-100 px-2 inset-ring py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">Sent Penawaran</span>
                                @elseif($item['type'] === 'custom_penawaran')
                                    <span class="inline-block rounded bg-cyan-100 px-2 inset-ring py-1 text-xs font-semibold text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">Custom Penawaran</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item['number'] }}</td>
                            <td class="px-4 py-3">{{ $item['customer'] }}</td>
                            <td class="px-4 py-3">{{ $item['sales'] }}</td>
                            <td class="px-4 py-3">{{ $item['grand_total'] }}</td>
                            <td class="px-4 py-3">
                                @if ($item['status'] === 'approved_supervisor' || $item['status'] === 'approved')
                                    <span class="inline-block rounded bg-green-100 px-2 inset-ring py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">Disetujui</span>
                                @elseif($item['status'] === 'rejected_supervisor' || $item['status'] === 'rejected')
                                    <span class="inline-block rounded bg-red-100 px-2 inset-ring py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">Ditolak</span>
                                @else
                                    <span class="inline-block rounded bg-gray-100 px-2 inset-ring py-1 text-xs font-semibold text-gray-800 dark:bg-gray-900 dark:text-gray-200">{{ $item['status'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $item['reason'] }}</td>
                            <td class="px-4 py-3">{{ $item['approved_at'] }}</td>
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
                <form method="GET" action="{{ route('supervisor.history') }}">
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
