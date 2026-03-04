<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-end overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('admin.sent_penawaran') }}" method="GET" class="block pl-2">
                <label for="topbar-search" class="sr-only">Search</label>
                <div class="relative md:w-96">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                            </path>
                        </svg>
                    </div>
                    <input type="search" name="search" id="topbar-search" value="{{ request('search') }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="Search" />
                </div>
            </form>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol px-4 py-3"></th>
                        <th class="px-4 py-3">No. Penawaran</th>
                        <th class="px-4 py-3">Sales</th>
                        <th class="px-4 py-3">To</th>
                        <th class="px-4 py-3">Items</th>
                        <th class="px-4 py-3">Diskon</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Sent At</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="w-fit px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penawarans as $index => $penawaran)
                        <tr class="group border-b border-gray-50 transition-colors hover:bg-gray-50/80 dark:border-gray-700/50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $penawaran->id }}" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900 dark:text-white">
                                        {{ $penawaran->offer_type === 'custom' ? $penawaran->penawaran_number : $penawaran->request_number }}
                                    </span>
                                    <span class="{{ $penawaran->offer_type === 'custom' ? 'text-blue-500' : 'text-amber-500' }} mt-0.5 text-[10px] font-semibold uppercase tracking-widest">
                                        {{ $penawaran->offer_type === 'custom' ? 'Custom Penawaran' : 'Request Order' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium">{{ optional($penawaran->sales)->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $penawaran->offer_type === 'custom' ? $penawaran->to : $penawaran->customer_name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inset-ring inline-flex items-center rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ $penawaran->items->count() }} Items
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $maxDiskon = $penawaran->items->max('diskon_percent') ?? 0;
                                    $itemsWithHighDiscount = $penawaran->items->where('diskon_percent', '>', 20);
                                @endphp
                                <div class="flex items-center">
                                    <span class="{{ $maxDiskon > 20 ? 'text-rose-600' : 'text-emerald-600' }} text-sm font-bold">{{ $maxDiskon }}%</span>
                                    @if ($maxDiskon > 20)
                                        <span class="ml-2 flex h-2 w-2 animate-pulse rounded-full bg-rose-500" title="High Discount"></span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if ($itemsWithHighDiscount->isNotEmpty())
                                    <div class="max-w-[200px] truncate text-xs italic text-gray-500 group-hover:overflow-visible group-hover:whitespace-normal">
                                        {{ $itemsWithHighDiscount->first()->keterangan ?? 'No reason provided' }}
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-gray-400">{{ $penawaran->created_at ? $penawaran->created_at->format('d M Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $status = $penawaran->order?->status ?? $penawaran->status;
                                    $statusClass =
                                        [
                                            'sent_to_supervisor' => 'bg-sky-50 text-sky-700 ring-sky-700/20',
                                            'open' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'approved_supervisor' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'rejected_supervisor' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                            'sent_to_warehouse' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/20',
                                        ][$status] ?? 'bg-gray-50 text-gray-600 ring-gray-600/20';
                                @endphp
                                <div class="flex items-center">
                                    <span class="{{ $statusClass }} badge inset-ring">
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </div>

                            </td>
                            <td class="w-fit px-4 py-3">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-20 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">


                                        {{-- Detail --}}
                                        @php
                                            $detailRoute = $penawaran->offer_type === 'custom' ? route('admin.custom-penawaran.show', $penawaran->id) : route('admin.request-order.show', $penawaran->id);
                                        @endphp
                                        <a href="{{ $detailRoute }}" class="group/btn flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Detail</span>
                                        </a>

                                        {{-- Approve --}}
                                        @php
                                            $approveRoute = $penawaran->offer_type === 'custom' ? route('admin.custom-penawaran.approval', $penawaran->id) : route('supervisor.request-order.approve', $penawaran->id);
                                        @endphp
                                        <form action="{{ $approveRoute }}" method="POST" class="approve-form m-0 border-l border-white/20 p-0" data-confirm-text="Apakah Anda yakin ingin menyetujui penawaran ini?">
                                            @csrf
                                            @if ($penawaran->offer_type === 'custom')
                                                <input type="hidden" name="action" value="approve">
                                            @endif
                                            <button type="submit" class="group/btn flex h-full cursor-pointer items-center justify-center bg-green-600 p-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" title="Setujui">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Setujui</span>
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <button type="button" class="group/btn flex h-full cursor-pointer items-center justify-center border-l border-white/20 bg-red-600 p-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="openTolakModal('{{ $penawaran->offer_type }}', '{{ $penawaran->id }}', '{{ $penawaran->offer_type === 'custom' ? $penawaran->penawaran_number : $penawaran->request_number }}')" title="Tolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle h-4 w-4">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="m15 9-6 6"></path>
                                                <path d="m9 9 6 6"></path>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover/btn:max-w-xs group-hover/btn:pl-2 group-hover/btn:opacity-100">Tolak</span>
                                        </button>
                                    </div>
                                </div>
                            </td>
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
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $penawarans->firstItem() ?? 0 }}-{{ $penawarans->lastItem() ?? 0 }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $penawarans->total() ?? $penawarans->count() }}</span>
                </span>
                <form method="GET" action="{{ route('admin.sent_penawaran') }}">
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
                {{ $penawarans->links() }}
            </div>
        </nav>
    </div>

    @include('admin.sent-penawaran.partials.modal_tolak')

</x-app-layout>
