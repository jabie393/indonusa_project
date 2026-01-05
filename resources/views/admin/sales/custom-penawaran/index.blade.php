<x-app-layout>
    <div
        class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm flex flex-col items-center justify-between space-y-3 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 md:flex-row md:space-x-4 md:space-y-0">

            <div>
                <h2 class="mr-3 font-semibold text-white">Daftar Penawaran Kustom</h2>
            </div>
            <div class="flex items-center space-x-4">
                {{-- Bulk Actions --}}
                <div id="bulk-actions" class="hidden flex-row items-center space-x-2"
                    data-delete-url="{{ route('sales.custom-penawaran.bulk-delete') }}"
                    data-sent-url="{{ route('sales.custom-penawaran.bulk-send-to-warehouse') }}">
                    <button id="bulk-delete"
                        class="flex items-center justify-center rounded-lg bg-red-700 px-4 py-2 text-sm font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300">
                        Delete Selected (<span id="selected-count">0</span>)
                    </button>
                    <button id="bulk-send"
                        class="flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        Send to Warehouse
                    </button>
                </div>
                <a href="{{ route('sales.custom-penawaran.create') }}"
                    class="rounded-lg bg-[#225A97] px-4 py-2 font-semibold text-white hover:bg-[#19426d]">
                    + Buat Penawaran Baru
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-400 bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-400 bg-red-100 px-4 py-3 text-red-700">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table id="DataTable" class="hover w-full text-left text-sm text-gray-500 dark:text-gray-400">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3"></th>
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
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $hasHighDiscountStatus = $penawaran->items->where('diskon', '>', 20)->isNotEmpty();
                                    $statusClass =
                                        [
                                            'draft' => 'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600',
                                            'pending_approval' => 'bg-orange-50 text-orange-800 inset-ring inset-ring-orange-600',
                                            'open' => 'bg-blue-50 text-blue-700 inset-ring inset-ring-blue-700',
                                            'sent' => 'bg-indigo-50 text-indigo-700 inset-ring inset-ring-indigo-700',
                                            'approved' => 'bg-green-50 text-green-700 inset-ring inset-ring-green-600',
                                            'rejected' => 'bg-red-50 text-red-700 inset-ring inset-ring-red-700',
                                            'expired' => 'bg-gray-50 text-gray-700 inset-ring inset-ring-gray-700',
                                        ][$penawaran->status] ??
                                        'bg-yellow-50 text-yellow-800 inset-ring inset-ring-yellow-600';
                                    $statusLabel =
                                        [
                                            'draft' => 'Draft',
                                            'pending_approval' => 'Menunggu Approve Supervisor',
                                            'open' => 'Open',
                                            'sent' => 'Terkirim',
                                            'approved' => 'Disetujui/Open',
                                            'rejected' => 'Ditolak',
                                            'expired' => 'Kadaluarsa',
                                            'sent_to_warehouse' => 'Dikirim ke Warehouse',
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
                                    <div
                                        class="absolute left-0 z-10 flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm dark:border-gray-600 dark:bg-gray-700">
                                        {{-- Detail --}}
                                        <a href="{{ route('sales.custom-penawaran.show', $penawaran->id) }}"
                                            class="group flex h-full items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye h-4 w-4">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                </path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Detail</span>
                                        </a>

                                        {{-- Action Dropdown --}}
                                        <button
                                            class="group flex h-full cursor-pointer items-center justify-center bg-blue-700 p-2 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                            popovertarget="popover-{{ $penawaran->id }}"
                                            style="anchor-name:--anchor-{{ $penawaran->id }}">
                                            <svg width="24px" height="24px" viewBox="0 0 16 16"
                                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                class="bi bi-three-dots-vertical h-4 w-4">
                                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                    stroke-linejoin="round"></g>
                                                <g id="SVGRepo_iconCarrier">
                                                    <path
                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z">
                                                    </path>
                                                </g>
                                            </svg>
                                            <span
                                                class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Aksi</span>
                                        </button>
                                        <ul class="dropdown dropdown-end menu rounded-box bg-base-100 w-52 shadow-sm"
                                            popover id="popover-{{ $penawaran->id }}"
                                            style="position-anchor:--anchor-{{ $penawaran->id }}">
                                            <li>
                                                {{-- Edit --}}
                                                <a href="{{ route('sales.custom-penawaran.edit', $penawaran->id) }}"
                                                    class="flex items-center gap-2 text-yellow-600 hover:bg-yellow-50">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="lucide lucide-pencil">
                                                        <path
                                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                        </path>
                                                        <path d="m15 5 4 4"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </li>
                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('sales.custom-penawaran.destroy', $penawaran->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <li>
                                                    <button type="button"
                                                        onclick="confirmDelete(() => this.closest('form').submit())"
                                                        class="flex w-full items-center gap-2 text-red-600 hover:bg-red-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-trash2">
                                                            <path d="M10 11v6"></path>
                                                            <path d="M14 11v6"></path>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                            <path d="M3 6h18"></path>
                                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </li>
                                            </form>
                                            <li>
                                                {{-- PDF --}}
                                                @php
                                                    // apakah ada item dengan diskon > 20%
                                                    $hasHighDiscount = $penawaran->items
                                                        ->where('diskon', '>', 20)
                                                        ->isNotEmpty();
                                                    $isExpired = $penawaran->isExpired();
                                                @endphp
                                                @if ($penawaran->status === 'open' && !$isExpired)
                                                    <a href="{{ route('sales.custom-penawaran.pdf', $penawaran->id) }}"
                                                        target="_blank"
                                                        class="flex items-center gap-2 text-green-600 hover:bg-green-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-file-text">
                                                            <path
                                                                d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
                                                            </path>
                                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                                            <path d="M10 9H8"></path>
                                                            <path d="M16 13H8"></path>
                                                            <path d="M16 17H8"></path>
                                                        </svg>
                                                        PDF
                                                    </a>
                                                @else
                                                    <button type="button" disabled
                                                        class="flex w-full cursor-not-allowed items-center gap-2 text-gray-400"
                                                        title="{{ $isExpired ? 'Penawaran sudah kadaluarsa' : 'Menunggu persetujuan Supervisor' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-file-text">
                                                            <path
                                                                d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z">
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
                                            @if (in_array($penawaran->status, ['open', 'approved']))
                                                <form
                                                    action="{{ route('sales.custom-penawaran.sent-to-warehouse', $penawaran->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <li>
                                                        <button type="submit"
                                                            class="flex w-full items-center gap-2 text-blue-600 hover:bg-blue-50"
                                                            title="Kirim ke Warehouse">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-truck">
                                                                <path
                                                                    d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2">
                                                                </path>
                                                                <path d="M15 18H9"></path>
                                                                <path
                                                                    d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14">
                                                                </path>
                                                                <circle cx="17" cy="18" r="2"></circle>
                                                                <circle cx="7" cy="18" r="2"></circle>
                                                            </svg>
                                                            Kirim ke Warehouse
                                                        </button>
                                                    </li>
                                                </form>
                                            @endif
                                        </ul>
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
