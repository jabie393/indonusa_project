<x-app-layout>

    <div class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div class="p-4">
            <a href="{{ route('sales.custom-penawaran.create') }}" class="flex flex-row items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                + Buat Penawaran Baru
            </a>
            {{-- Bulk Actions --}}
            <div id="bulk-actions" class="hidden flex-row items-center space-x-2" data-delete-url="{{ route('sales.custom-penawaran.bulk-delete') }}" data-sent-url="{{ route('sales.custom-penawaran.bulk-send-to-warehouse') }}">
                <button id="bulk-delete" class="flex items-center justify-center rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
                    Delete Selected (<span id="selected-count">0</span>)
                </button>
                <button id="bulk-send" class="flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Send to Warehouse
                </button>
            </div>

        </div>

        <div class="p-4">
            {{-- Search --}}
            <form action="{{ route('sales.custom-penawaran.index') }}" method="GET" class="block pl-2">
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
        <div class="bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
        </div>
        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="selectCol px-4 py-3"></th>
                        <th class="px-4 py-3">No Penawaran</th>
                        <th class="px-4 py-3">Kepada</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Our Ref</th>
                        <th class="px-4 py-3">Diskon</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Tanggal Dibuat</th>
                        <th class="px-4 py-3">Berlaku Sampai</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customPenawarans as $penawaran)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-4">{{ $penawaran->id }}</td>
                            <td class="px-4 py-4">
                                <span class="text-nowrap font-semibold">{{ $penawaran->penawaran_number }}</span>
                            </td>
                            <td class="text-nowrap px-4 py-4">{{ $penawaran->to }}</td>
                            <td class="px-4 py-4">{{ Str::limit($penawaran->subject, 30) }}</td>
                            <td class="text-nowrap px-4 py-4">{{ $penawaran->our_ref }}</td>
                            <td class="px-4 py-4">
                                @php
                                    $discounts = $penawaran->items->pluck('diskon')->unique()->sort()->values();
                                @endphp
                                @if ($discounts->isNotEmpty())
                                    @foreach ($discounts as $index => $d)
                                        <span class="badge bg-green-50 text-green-700">{{ $d }}%</span>
                                        @if ($index < $discounts->count() - 1)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    <span class="badge bg-gray-50 text-gray-700">0%</span>
                                @endif
                            </td>
                            <td class="text-nowrap px-4 py-4 text-right font-semibold">
                                Rp {{ number_format($penawaran->grand_total ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                {{ \Carbon\Carbon::parse($penawaran->created_at)->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($penawaran->expired_at)
                                    {{ \Carbon\Carbon::parse($penawaran->expired_at)->format('d/m/Y') }}
                                    @if ($penawaran->isExpired())
                                        <br><small class="text-danger">Kadaluarsa</small>
                                    @else
                                        @php
                                            $daysLeft = (int) $penawaran->expired_at->diffInDays(now());
                                        @endphp
                                        <br><small class="text-success">Berlaku {{ $daysLeft }} hari lagi</small>
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($penawaran->created_at)->addDays(14)->format('d/m/Y') }}
                                    <br><small class="text-success">Berlaku 14 hari dari dibuat</small>
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusClass = [
                                        'draft' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                        'pending_approval' => 'bg-orange-50 text-orange-800 inset-ring inset-ring-orange-600',
                                        'open' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-700',
                                        'sent' => 'bg-indigo-50 text-indigo-700 inset-ring inset-ring-indigo-700',
                                        'approved' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                        'rejected' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                        'expired' => 'bg-gray-50 text-gray-700 inset-ring inset-ring-gray-700',
                                        'approved_supervisor' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                        'rejected_supervisor' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                    ][$penawaran->status] ?? 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600';
                                    $statusLabel = [
                                        'draft' => 'Draft',
                                        'pending_approval' => 'Menunggu Approve Supervisor',
                                        'open' => 'Open',
                                        'sent' => 'Terkirim',
                                        'approved' => 'Disetujui/Open',
                                        'rejected' => 'Ditolak',
                                        'expired' => 'Kadaluarsa',
                                        'approved_supervisor' => 'Disetujui Supervisor',
                                        'rejected_supervisor'  => 'Ditolak Supervisor',
                                    ][$penawaran->status] ?? $penawaran->status;
                                @endphp
                                <div class="flex items-center justify-center gap-2">
                                    <span class="{{ $statusClass }} badge">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </td>
                            <td class="w-fit px-4 py-3 text-right">
                                <div class="relative flex min-h-[40px] w-fit items-center justify-end">
                                    <div class="pointer-events-none invisible h-9 w-32 opacity-0">Placeholder</div>
                                    <div class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}" class="group flex h-full items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4">
                                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- Action Dropdown --}}
                                        <button class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" popovertarget="popover-{{ $penawaran->id }}" style="anchor-name:--anchor-{{ $penawaran->id }}">
                                            <svg width="24px" height="24px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-three-dots-vertical h-4 w-4">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Aksi</span>
                                        </button>
                                        <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm" popover id="popover-{{ $penawaran->id }}" style="position-anchor:--anchor-{{ $penawaran->id }}">
                                            <li>
                                                {{-- Edit --}}
                                                <a href="{{ route('sales.custom-penawaran.edit', $penawaran->id) }}"
                                                   class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-indigo-500 to-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-indigo-600 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z" /></svg>
                                                    Edit
                                                </a>
                                            </li>
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('sales.custom-penawaran.destroy', $penawaran->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus penawaran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <li>
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-red-500 to-red-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-red-600 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        Hapus
                                                    </button>
                                                </li>
                                            </form>
                                            <li>
                                                {{-- PDF --}}
                                                @php
                                                    // apakah ada item dengan diskon > 20%
                                                    $hasHighDiscount = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                                    $isExpired = $penawaran->isExpired();
                                                @endphp
                                                @if ($penawaran->status === 'approved_supervisor' && !$isExpired)
                                                    <a href="{{ route('sales.custom-penawaran.pdf', $penawaran->id) }}"
                                                       class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-500 to-green-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-green-600 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-400"
                                                       target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                        Download PDF
                                                    </a>
                                                @else
                                                    <button type="button" disabled class="flex w-full cursor-not-allowed items-center gap-2 text-gray-400" title="{{ $isExpired ? 'Penawaran sudah kadaluarsa' : 'Menunggu persetujuan Supervisor' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text">
                                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
                                                            </path>
                                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                            <path d="M10 9H8"></path>
                                                            <path d="M16 13H8"></path>
                                                            <path d="M16 17H8"></path>
                                                        </svg>
                                                        PDF
                                                    </button>
                                                @endif
                                            </li>
                                            {{-- Sent to Warehouse --}}
                                            @if ($penawaran->status === 'approved_supervisor')
                                                <form action="{{ route('sales.custom-penawaran.sent-to-warehouse', $penawaran->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-yellow-600 hover:to-yellow-800 focus:outline-none focus:ring-2 focus:ring-yellow-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h2l1 2h13l1-2h2M5 12v6a2 2 0 002 2h10a2 2 0 002-2v-6" /></svg>
                                                        Kirim ke Warehouse
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Sent to Penawaran --}}
                                            @if (in_array($penawaran->status, ['open', 'approved_supervisor']))
                                                <form action="{{ route('sales.custom-penawaran.sent-to-penawaran', $penawaran->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-700 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:from-blue-600 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                                        Sent to Penawaran
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Action Dropdown --}}








                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($customPenawarans->hasPages())
            <div class="mt-6">
                {{ $customPenawarans->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
    @vite(['resources/js/custom-penawaran.js'])
</x-app-layout>
