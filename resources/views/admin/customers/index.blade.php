<x-app-layout>
    <div class="flex flex-col lg:h-[calc(100vh-112px)] overflow-hidden">
        <div
            class="inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative mb-5 flex h-16 items-center justify-between overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800 shrink-0">

            <div class="px-4">
                <button onclick="createCustomerModal.showModal()"
                    class="flex items-center justify-center rounded-lg bg-[#225A97] px-4 py-2 text-sm font-medium text-white hover:bg-[#19426d] focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-[#225A97] dark:focus:ring-primary-800">
                    <svg class="mr-2 h-3.5 w-3.5" fill="currentColor" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    Tambah Customers
                </button>
            </div>

            <div class="mr-5 flex items-center space-x-3 md:w-auto">
                {{-- Search --}}
                <form action="{{ route('customers.index') }}" method="GET" class="block pl-2" data-realtime-table-search data-search-input="#topbar-search"
                    data-search-target="#tableContainer" data-pagination-target="#pagination-nav" data-extra-fields="#pagination-nav select[name='perPage']">
                    <label for="topbar-search" class="sr-only">Search</label>
                    <div class="relative md:w-96">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                </path>
                            </svg>
                        </div>
                        <input type="search" name="search" id="topbar-search" aria-controls="warehouseTable"
                            value="{{ request('search') }}"
                            class="dt-input block w-full rounded-lg bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            placeholder="Search" />
                    </div>
                </form>
            </div>

        </div>

        <div
            class="relative flex flex-1 min-h-0 flex-col overflow-hidden rounded-2xl bg-white shadow-md dark:bg-gray-800">
            <div class="shrink-0 bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4">
            </div>
            <div id="tableContainer" class="grow overflow-x-auto overflow-y-auto">
                <table class="sortable w-full text-left text-sm text-gray-500 dark:text-gray-400" id="">
                    <thead
                        class="sticky top-0 z-30 bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="text-nowrap px-4 py-3">Customers</th>
                            <th class="text-nowrap px-4 py-3">Term & Kredit</th>
                            <th class="text-nowrap px-4 py-3">PIC & Kontak</th>
                            <th class="text-nowrap px-4 py-3">Status</th>
                            <th class="flex justify-end text-nowrap px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr
                                class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors duration-200 dark:border-gray-700 dark:hover:bg-gray-800/30">
                                {{-- Customer Column --}}
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span
                                                class="font-bold text-gray-900 dark:text-white text-[15px]">{{ $customer->nama_customer }}</span>
                                            @php
                                                $tipe = strtoupper($customer->tipe_customer);
                                                $badgeClass = 'text-gray-600 border border-gray-400 bg-gray-50/50 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-900/30';
                                                if ($tipe === 'SWASTA') {
                                                    $badgeClass = 'text-blue-700 border border-blue-500 bg-blue-50/50 dark:text-blue-400 dark:border-blue-700 dark:bg-blue-950/30';
                                                } elseif ($tipe === 'PEMERINTAH') {
                                                    $badgeClass = 'text-cyan-600 border border-cyan-500 bg-cyan-50/50 dark:text-cyan-400 dark:border-cyan-700 dark:bg-cyan-950/30';
                                                } elseif ($tipe === 'BUMN') {
                                                    $badgeClass = 'text-red-600 border border-red-500 bg-red-50/50 dark:text-red-400 dark:border-red-700 dark:bg-red-950/30';
                                                }
                                            @endphp
                                            <span
                                                class="rounded px-1.5 py-0.5 text-[10px] font-bold tracking-wider uppercase {{ $badgeClass }}">
                                                {{ $customer->tipe_customer }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-400">NPWP:</span>
                                            {{ !empty($customer->npwp) ? $customer->npwp : '-' }}
                                        </div>
                                    </div>
                                </td>

                                {{-- Term & Kredit Column --}}
                                <td class="px-4 py-3">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-sm">
                                            <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-semibold text-gray-800 dark:text-gray-200">
                                                {{ !empty($customer->term_of_payments) ? $customer->term_of_payments . ' Hari' : '-' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm">
                                            <svg class="h-4 w-4 text-blue-500/80 shrink-0" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <span class="font-bold">
                                                Rp{{ $customer->kredit_limit !== null && is_numeric($customer->kredit_limit) ? number_format($customer->kredit_limit, 0, ',', '.') : $customer->kredit_limit ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- PIC & Kontak Column --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-start gap-2">
                                        <svg class="h-4 w-4 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <div class="flex flex-col gap-1">
                                            @if ($customer->pics && $customer->pics->count() > 0)
                                                @foreach ($customer->pics as $pic)
                                                    <div class="mb-0.5">
                                                        <div class="font-bold text-gray-800 dark:text-gray-200">{{ $pic->name }}
                                                        </div>
                                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                                            {{ $pic->position }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif

                                            <div
                                                class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                <svg class="h-3 w-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $customer->telepon }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                                <svg class="h-3 w-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $customer->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Column --}}
                                @if (in_array(auth()->user()->role, ['Supervisor']))
                                                    <td class="px-4 py-3 align-middle">
                                                        <div class="relative inline-block w-32">
                                                            <select onchange="updateCustomerStatus({{ $customer->id }}, this.value)"
                                                                class="w-full appearance-none rounded-full px-3 py-1.5 pr-8 text-xs font-semibold tracking-wide border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 cursor-pointer
                                                                                                                    {{ strtolower($customer->status) == 'active'
                                    ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100 focus:ring-green-500 focus:border-green-500 dark:bg-green-950/30 dark:text-green-400 dark:border-green-800/50'
                                    : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100 focus:ring-red-500 focus:border-red-500 dark:bg-red-950/30 dark:text-red-400 dark:border-red-800/50' }}">
                                                                <option value="active" {{ strtolower($customer->status) == 'active' ? 'selected' : '' }} class="bg-white text-green-700 dark:bg-gray-800">
                                                                    🟢 Aktif
                                                                </option>
                                                                <option value="inactive" {{ strtolower($customer->status) != 'active' ? 'selected' : '' }} class="bg-white text-red-700 dark:bg-gray-800">
                                                                    🔴 Non-Aktif
                                                                </option>
                                                            </select>
                                                            <div
                                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 
                                                                                                                    {{ strtolower($customer->status) == 'active' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2.5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </td>
                                @elseif (in_array(auth()->user()->role, ['Sales', 'General Affair']))
                                    <td class="px-4 py-3 align-middle">
                                        @php
                                            $statusLabel = [
                                                'active' => 'Aktif',
                                                'inactive' => 'Non-Aktif',
                                            ][$customer->status] ?? $customer->status;

                                            $badgeBg = 'bg-gray-50 dark:bg-gray-900/30';
                                            $badgeText = 'text-gray-700 dark:text-gray-300';
                                            $badgeBorder = 'border border-gray-200 dark:border-gray-700/50';
                                            $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/></svg>';

                                            if ($customer->status === 'active') {
                                                $badgeBg = 'bg-green-50 dark:bg-green-950/30';
                                                $badgeText = 'text-green-700 dark:text-green-300';
                                                $badgeBorder = 'border border-green-200 dark:border-green-800/50';
                                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>';
                                            } elseif ($customer->status === 'inactive') {
                                                $badgeBg = 'bg-red-50 dark:bg-red-950/30';
                                                $badgeText = 'text-red-700 dark:text-red-300';
                                                $badgeBorder = 'border border-red-200 dark:border-red-800/50';
                                                $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle mr-1.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>';
                                            }
                                        @endphp
                                        <span
                                            class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} {{ $badgeBorder }}">
                                            {!! $iconSvg !!}{{ $statusLabel }}
                                        </span>
                                    </td>
                                @else
                                    <td class="px-4 py-3 align-middle">
                                        <span
                                            class="inline-flex items-center justify-center rounded-full px-2.5 py-1 text-xs font-semibold {{ strtolower($customer->status) == 'active' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ strtolower($customer->status) == 'active' ? 'Aktif' : 'Non-Aktif' }}
                                        </span>
                                    </td>
                                @endif

                                {{-- Action Column --}}
                                <td class="whitespace-nowrap px-4 py-3 text-right align-middle">
                                    <div class="flex justify-end">
                                        <div
                                            class="inline-flex flex-row overflow-hidden rounded-lg border border-gray-300 bg-white shadow-sm transition-all duration-300 ease-in-out dark:border-gray-600 dark:bg-gray-700">
                                            {{-- Edit --}}
                                            <button onclick="openEditModal({{ $customer->toJson() }})"
                                                class="edit-barang-btn group flex h-full cursor-pointer items-center justify-center border-r border-blue-800 bg-blue-700 p-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-500 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-pencil h-4 w-4">
                                                    <path
                                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                    </path>
                                                    <path d="m15 5 4 4"></path>
                                                </svg>
                                                <span
                                                    class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Edit</span>
                                            </button>
                                            {{-- Delete --}}
                                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="group flex h-full cursor-pointer items-center justify-center bg-red-700 p-2.5 text-sm font-medium text-white transition-all duration-300 ease-in-out hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                    onclick="confirmDelete(() => this.closest('form').submit())">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-trash2 lucide-trash-2 h-4 w-4">
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2 2V6"></path>
                                                        <path d="M3 6h18"></path>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                    <span
                                                        class="max-w-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out group-hover:max-w-xs group-hover:pl-2 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <nav id="pagination-nav"
                class="sticky bottom-0 z-20 flex shrink-0 flex-col items-start justify-between space-y-3 border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800 md:flex-row md:items-center md:space-y-0"
                aria-label="Table navigation">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        Menampilkan
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $customers->firstItem() ?? 0 }}-{{ $customers->lastItem() ?? 0 }}</span>
                        dari
                        <span
                            class="font-semibold text-gray-900 dark:text-white">{{ $customers->total() ?? $customers->count() }}</span>
                    </span>
                    <form method="GET" action="{{ route('customers.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" onchange="this.form.submit()"
                            class="mx-2 rounded-xl border border-gray-300 bg-gray-50 p-1 pl-2 pr-8 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach ([10, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('perPage', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <span class="text-sm text-gray-500 dark:text-gray-400">per halaman</span>
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </nav>

        </div>
    </div>

    <!-- Modals -->
    @include('admin.customers.partials.customers-modal-tambah')
    @include('admin.customers.partials.customers-modal-edit')

    <script>
        function updateCustomerStatus(id, newStatus) {
            Swal.fire({
                title: 'Sedang memproses...',
                allowOutsideClick: false,
                customClass: {
                    popup: 'rounded-2xl!',
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('customers.status.update', ['id' => ':id']) }}".replace(':id', id),
                type: 'PATCH',
                data: {
                    status: newStatus,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: 'rounded-2xl!',
                            },
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            customClass: {
                                popup: 'rounded-2xl!',
                            },
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.',
                        customClass: {
                            popup: 'rounded-2xl!',
                        },
                    });
                }
            });
        }
    </script>
    @vite(['resources/js/realtime-table-search.js', 'resources/js/table-sort.js'])
</x-app-layout>
